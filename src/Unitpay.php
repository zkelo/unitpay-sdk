<?php

namespace zkelo\Unitpay;

use InvalidArgumentException;
use Symfony\Component\HttpClient\{
    HttpClient,
    HttpClientInterface
};
use Symfony\Contracts\HttpClient\ResponseInterface;
use zkelo\Unitpay\Exceptions\{
    ApiException,
    InvalidConfigException
};
use zkelo\Unitpay\Models\{
    Currency,
    Locale,
    Operator,
    Payment,
    PaymentInfo
};

/**
 * Unitpay SDK
 *
 * @author Aleksandr Riabov <ar161ru@gmail.com>
 * @version 1.0.0
 * @see https://help.unitpay.ru
 */
class Unitpay
{
    /**
     * Signature params delimiter
     */
    const SIGNATURE_DELIMITER = '{up}';

    /**
     * Domain
     *
     * It will be used to make requests to API
     *
     * @var string
     */
    protected $domain = 'unitpay.ru';

    /**
     * Public key
     *
     * @var string
     */
    protected $publicKey = '';

    /**
     * Project ID
     *
     * You don't need to pass this param to SDK constructor. It will be automatically extracted from first part of your public key.
     *
     * @var integer
     */
    protected $projectId = 0;

    /**
     * Secret key
     *
     * @var string
     */
    private $secretKey = '';

    /**
     * List of available Unitpay domains
     *
     * @var array
     */
    private $availableDomains = [
        'unitpay.ru',
        'unitpay.money'
    ];

    /**
     * Default payment method
     *
     * @var string
     */
    private $defaultPaymentMethod = Payment::METHOD_CARD;

    /**
     * Default locale
     *
     * @var string
     */
    private $defaultLocale;

    /**
     * Locale
     *
     * @var Locale
     */
    private $locale;

    /**
     * Test mode
     *
     * @var boolean
     */
    private $testMode = false;

    /**
     * HTTP client that will be used to make requests
     *
     * @var HttpClientInterface
     */
    private $client;

    /**
     * Constructs a new SDK instance
     *
     * @param string $secretKey Secret key
     * @param string $publicKey Public key
     * @param string|null $domain Domain _(default is `unitpay.ru`)_
     * @return void
     * @throws InvalidConfigException If some of passed params has invalid value
     */
    public function __construct(string $secretKey, string $publicKey, ?string $domain = null)
    {
        if (empty($secretKey)) {
            throw new InvalidConfigException('Secret key is required');
        }
        if (empty($publicKey)) {
            throw new InvalidConfigException('Public key is required');
        }
        if (!is_null($domain)) {
            if (!in_array($domain, $this->availableDomains)) {
                throw new InvalidConfigException('Specified domain is not supported');
            }
        }

        $this->secretKey = $secretKey;
        $this->publicKey = $publicKey;
        $this->domain = $domain ?? $this->availableDomains[0];

        $projectData = explode('-', $this->publicKey, 2);
        $this->projectId = array_shift($projectData);
        if (is_null($this->projectId)) {
            throw new InvalidArgumentException('Unable to extract project ID from public key');
        }
        $this->projectId = intval($this->projectId);

        $this->client = HttpClient::create();
        $this->setDefaultLocale(Locale::ENGLISH);
    }

    /**
     * Enables or disables test mode
     *
     * @param boolean $toggle `true` to enable or `false` to disable
     * @return void
     */
    public function toggleTestMode(bool $toggle): void
    {
        $this->testMode = $toggle;
    }

    /**
     * Changes default payment method
     *
     * @param string $method Method
     * @return void
     * @throws InvalidArgumentException If specified payment method is not supported
     */
    public function setDefaultPaymentMethod(string $method): void
    {
        if (!Payment::isMethodSupported($method)) {
            throw new InvalidArgumentException('Specified payment method is not supported');
        }

        $this->defaultPaymentMethod = $method;
    }

    /**
     * Changes default locale
     *
     * @param string $locale Locale
     * @return void
     */
    public function setDefaultLocale(string $locale): void
    {
        if (!Locale::isSupported($locale)) {
            throw new InvalidArgumentException('Specified locale is not supported');
        }

        $this->defaultLocale = $locale;
        $this->locale = new Locale($this->defaultLocale);
    }

    /**
     * Creates link to payment form
     *
     * @param float $sum Amount
     * @param string $account Account ID _(for example, it can be email or order ID)_
     * @param string $description Order description
     * @param string|null $paymentMethod Payment method _(default is bank cards)_. You could use one of constants that starts with `METHOD_` were provided by `Payment` model. List of supported payment methods can be found [here](https://help.unitpay.ru/v/master/book-of-reference/payment-system-codes)
     * @param string|null $currency Order currency according ISO 4217 standard (default is `RUB`). You could use one of constants provided by `Currency` model. List of supported currencies can be found [here](https://help.unitpay.ru/v/master/book-of-reference/currency-codes)
     * @param string|null $locale Locale code. You could use one of constants provided by `Locale` model
     * @param string|null $backUrl URL that could be used for back redirect after payment processing
     * @return string
     */
    public function form(float $sum, string $account, string $description, ?string $paymentMethod = null, ?string $currency = null, ?string $locale = null, ?string $backUrl = null): string
    {
        if ($sum <= 0) {
            throw new InvalidArgumentException('Amount can\'t be less than or equal 0');
        }

        if (empty($account)) {
            throw new InvalidArgumentException('Account ID is required');
        }

        if (empty($description)) {
            throw new InvalidArgumentException('Order description is required');
        }

        if (!empty($paymentMethod)) {
            if (!Payment::isMethodSupported($paymentMethod)) {
                throw new InvalidArgumentException("Specified payment method \"$paymentMethod\" is not supported");
            }
        }

        $params = compact('sum', 'account');
        $params['desc'] = $description;

        if (!empty($currency)) {
            if (!Currency::isSupported($currency)) {
                throw new InvalidArgumentException("Specified currency \"$currency\" is not supported");
            }
        }
        $params['currency'] = $currency;

        if (!empty($locale)) {
            if (!Locale::isSupported($locale)) {
                throw new InvalidArgumentException("Specified locale \"$locale\" is not supported");
            }
            $params['locale'] = $locale;
        }

        if (!empty($backUrl)) {
            $params['backUrl'] = $backUrl;
        }

        $params['signature'] = $this->signature($params['account'], $params['desc'], $params['sum'], $params['currency']);

        if ($this->testMode) {
            $params['test'] = true;
        }

        $url = $this->baseUrl();
        $url .= '/pay';
        $url .= '/' . $this->publicKey;
        $url .= '/' . ($paymentMethod ?? $this->defaultPaymentMethod);
        $url .= '?' . http_build_query($params);
        return $url;
    }

    /**
     * Initializes payment
     *
     * @param string $method ayment method _(default is bank cards)_. You could use one of constants that starts with `METHOD_` were provided by `Payment` model. List of supported payment methods can be found [here](https://help.unitpay.ru/v/master/book-of-reference/payment-system-codes)
     * @param string $account Account ID _(for example, it can be email or order ID)_
     * @param float $sum Amount
     * @param string $description Order description
     * @param string $ip IP address
     * @param string|null $resultUrl Page URL that could be used as payment result page. If not specified, then payment receipt page will be used
     * @param string|null $phone Phone
     * @param string|null $operator Operator
     * @return integer|null Payment ID or `null` if payment failed be created
     * @throws InvalidArgumentException If some of passed param has invalid value
     * @throws ApiException If API response is invalid
     */
    public function initPayment(string $method, string $account, float $sum, string $description, string $ip, ?string $resultUrl = null, ?string $phone = null, ?string $operator = null): ?int
    {
        if (!Payment::isMethodSupported($method)) {
            throw new InvalidArgumentException("Specified payment method \"$method\" is not supported");
        }
        if (empty($account)) {
            throw new InvalidArgumentException('Account ID is required');
        }
        if ($sum <= 0) {
            throw new InvalidArgumentException('Amount can\'t be less than or equal 0');
        }
        if (empty($description)) {
            throw new InvalidArgumentException('Order description is required');
        }
        if (!filter_var($ip, FILTER_VALIDATE_IP)) {
            throw new InvalidArgumentException('Invalid IP');
        }

        $params = compact('account', 'sum', 'ip');
        $params['paymentType'] = $method;
        $params['projectId'] = $this->projectId;
        $params['desc'] = $description;

        if (!empty($resultUrl)) {
            $params['resultUrl'] = $resultUrl;
        }
        if (!empty($phone)) {
            $params['phone'] = $phone;
        }
        if (!empty($operator)) {
            if (!Operator::isSupported($operator)) {
                throw new InvalidArgumentException("Specified operator \"$operator\" is not supported");
            }
        }

        $params['secretKey'] = $this->secretKey;
        $params['signature'] = $this->signature($params['account'], $params['desc'], $params['sum']);

        $response = $this->api('initPayment', $params);
        return $response['paymentId'];
    }

    /**
     * Returns information about payment
     *
     * @param integer $id Payment ID
     * @return PaymentInfo|null Information about payment or `null` if got an error
     * @throws InvalidArgumentException If passed payment ID is invalid
     * @throws ApiException If API response is invalid
     */
    public function getPayment(int $id): ?PaymentInfo
    {
        if ($id <= 0) {
            throw new InvalidArgumentException('Payment ID must be greater than 0');
        }

        $params = [
            'paymentId' => $id,
            'secretKey' => $this->secretKey
        ];

        $response = $this->api('getPayment', $params);
        return new PaymentInfo($response);
    }

    /**
     * Makes request to the API
     *
     * @param string $method Method name
     * @param array $params Params
     * @return mixed Response
     * @throws ApiException If API response is invalid
     */
    protected function api(string $method, array $params)
    {
        if ($this->testMode) {
            $params['test'] = true;
        }

        $url = $this->baseUrl();
        $url .= '/api';

        $response = $this->client->request('GET', $url, [
            'query' => compact('method', 'params')
        ]);

        $content = $response->toArray(false);
        if (isset($content['error'])) {
            if (!isset($content['error']['message'])) {
                throw new ApiException('Unknown API error');
            }
            throw new ApiException($content['error']['message']);
        }

        if (!isset($content['result'])) {
            throw new ApiException('API response doesn\'t have field "result"');
        }

        return $content['result'];
    }

    /**
     * Calculates request signature
     *
     * @param string $account Account ID
     * @param string $description Order description
     * @param float $sum Amount
     * @param string|null $currency Currency
     * @return string Signature
     */
    protected function signature(string $account, string $description, float $sum, ?string $currency = null): string
    {
        $params = compact('account', 'currency', 'description', 'sum');
        $params[] = $this->secretKey;
        $params = array_filter($params);
        $params = implode(self::SIGNATURE_DELIMITER, $params);

        return hash('sha256', $params);
    }

    /**
     * Returns base URL
     *
     * @return string
     */
    protected function baseUrl(): string
    {
        $url = 'https://' . $this->domain;
        return $url;
    }
}
