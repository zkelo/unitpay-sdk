<?php

namespace zkelo\Unitpay;

use InvalidArgumentException;
use Symfony\Component\HttpClient\{
    HttpClient,
    HttpClientInterface,
    ResponseInterface
};
use zkelo\Unitpay\Exceptions\{
    ApiException,
    InvalidConfigException
};
use zkelo\Unitpay\Models\Currency;

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
     * Метод входящего запроса: CHECK
     *
     * Проверка возможности оказания услуг абоненту. Запрос выполняется до выполнения оплаты
     */
    const REQUEST_METHOD_CHECK = 'check';

    /**
     * Метод входящего запроса: PAY
     *
     * Уведомление об успешном платеже
     */
    const REQUEST_METHOD_PAY = 'pay';

    /**
     * Метод входящего запроса: PREAUTH
     *
     * Уведомление о платеже с преавторизацией, когда средства были успешно заблокированы
     */
    const REQUEST_METHOD_PREAUTH = 'preAuth';

    /**
     * Метод входящего запроса: ERROR
     *
     * Ошибка платежа на любой из этапов. Если ошибка вызвана пустым или ошибочным ответом сервера партнёра, то запрос не будет отправлен. Следует учесть, что данный статус не конечный и возможны ситуации, когда после запроса ERROR может последовать запрос PAY
     */
    const REQUEST_METHOD_ERROR = 'error';

    /**
     * Разделитель параметров в подписи запроса
     */
    const SIGNATURE_DELIMITER = '{up}';

    /**
     * Способ оплаты: Мобильные телефоны
     */
    const PAYMENT_METHOD_MOBILE = 'mc';

    /**
     * Способ оплаты: Банковские карты
     */
    const PAYMENT_METHOD_CARD = 'card';

    /**
     * Способ оплаты: Кошелёк Webmoney Z _(долларовый кошелёк)_
     */
    const PAYMENT_METHOD_WEBMONEY_Z = 'webmoney';

    /**
     * Способ оплаты: Кошелёк Webmoney R _(рублёвый кошелёк)_
     */
    const PAYMENT_METHOD_WEBMONEY_R = 'webmoneyWmr';

    /**
     * Способ оплаты: ЮMoney _(бывшие "Яндекс.Деньги")_
     */
    const PAYMENT_METHOD_YOOMONEY = 'yandex';

    /**
     * Способ оплаты: QIWI
     */
    const PAYMENT_METHOD_QIWI = 'qiwi';

    /**
     * Способ оплаты: Paypal
     */
    const PAYMENT_METHOD_PAYPAL = 'paypal';

    /**
     * Способ оплаты: Apple Pay
     */
    const PAYMENT_METHOD_APPLE_PAY = 'applepay';

    /**
     * Способ оплаты: Samsung Pay
     */
    const PAYMENT_METHOD_SAMSUNG_PAY = 'samsungpay';

    /**
     * Способ оплаты: Google Pay
     */
    const PAYMENT_METHOD_GOOGLE_PAY = 'googlepay';

    /**
     * Язык: Английский
     */
    const LOCALE_ENGLISH = 'en';

    /**
     * Язык: Русский
     */
    const LOCALE_RUSSIAN = 'ru';

    /**
     * Оператор: МТС
     */
    const OPERATOR_MTS = 'mts';

    /**
     * Оператор: Мегафон
     */
    const OPERATOR_MEGAFON = 'mf';

    /**
     * Оператор: Билайн
     */
    const OPERATOR_BEELINE = 'beeline';

    /**
     * Оператор: Теле2
     */
    const OPERATOR_TELE2 = 'tele2';

    /**
     * Домен
     *
     * @var string
     */
    protected $domain = 'unitpay.ru';

    /**
     * Публичный ключ
     *
     * @var string
     */
    protected $publicKey = '';

    /**
     * Номер проекта в системе Unitpay
     *
     * @var integer
     */
    protected $projectId = 0;

    /**
     * Список возможных методов при входящем запросе от Unitpay
     *
     * @var array
     */
    protected $allowedRequestMethods = [
        self::REQUEST_METHOD_CHECK,
        self::REQUEST_METHOD_ERROR,
        self::REQUEST_METHOD_PAY,
        self::REQUEST_METHOD_PREAUTH
    ];

    /**
     * Доступные способы оплаты
     *
     * @var array
     */
    protected $availablePaymentMethods = [
        self::PAYMENT_METHOD_MOBILE => 'Мобильный платёж',
        self::PAYMENT_METHOD_CARD => 'Банковские карты',
        self::PAYMENT_METHOD_WEBMONEY_Z => 'WebMoney Z-',
        self::PAYMENT_METHOD_WEBMONEY_R => 'WebMoney R-',
        self::PAYMENT_METHOD_YOOMONEY => 'ЮMoney (бывшие "Яндекс.Деньги")',
        self::PAYMENT_METHOD_QIWI => 'Qiwi',
        self::PAYMENT_METHOD_PAYPAL => 'PayPal',
        self::PAYMENT_METHOD_APPLE_PAY => 'Apple Pay',
        self::PAYMENT_METHOD_SAMSUNG_PAY => 'Samsung Pay',
        self::PAYMENT_METHOD_GOOGLE_PAY => 'Google Pay'
    ];

    /**
     * Доступные языки
     *
     * @var array
     */
    protected $availableLocales = [
        self::LOCALE_ENGLISH,
        self::LOCALE_RUSSIAN
    ];

    /**
     * Доступные операторы
     *
     * @var array
     */
    protected $availableOperators = [
        self::OPERATOR_MTS => 'МТС',
        self::OPERATOR_MEGAFON => 'Мегафон',
        self::OPERATOR_BEELINE => 'Билайн',
        self::OPERATOR_TELE2 => 'Теле2'
    ];

    /**
     * Секретный ключ
     *
     * @var string
     */
    private $secretKey = '';

    /**
     * Список IP-адресов серверов Unitpay
     *
     * @var string[]
     */
    private $ips = [
        '31.186.100.49',
        '178.132.203.105',
        '52.29.152.23',
        '52.19.56.234'
    ];

    /**
     * Список доступных доменов
     *
     * @var array
     */
    private $availableDomains = [
        'unitpay.ru',
        'unitpay.money'
    ];

    /**
     * Способ оплаты, используемый по умолчанию
     *
     * @var string
     */
    private $defaultPaymentMethod = self::PAYMENT_METHOD_CARD;

    /**
     * Тестовый режим
     *
     * @var boolean
     */
    private $testMode = false;

    /**
     * HTTP-клиент для выполнения запросов
     *
     * @var HttpClientInterface
     */
    private $client;

    /**
     * Конструктор
     *
     * @param string $secretKey Секретный ключ
     * @param string $publicKey Публичный ключ
     * @param string|null $domain Домен _(по умолчанию - `unitpay.ru`)_
     * @return void
     * @throws InvalidConfigException Если у какого-то из переданных аргументов некорректное значение
     */
    public function __construct(string $secretKey, string $publicKey, ?string $domain = null)
    {
        if (empty($secretKey)) {
            throw new InvalidConfigException('Не передан секретный ключ');
        }
        if (empty($publicKey)) {
            throw new InvalidConfigException('Не передан публичный ключ');
        }
        if (!is_null($domain)) {
            if (!in_array($domain, $this->availableDomains)) {
                throw new InvalidConfigException('Указанный домен недоступен для использования');
            }
        }

        $this->secretKey = $secretKey;
        $this->publicKey = $publicKey;
        $this->domain = $domain ?? $this->availableDomains[0];

        $projectData = explode('-', $this->publicKey, 2);
        if ($projectData === false) {
            throw new InvalidArgumentException('Не удаётся извлечь номер проекта из публичного ключа');
        }

        $this->projectId = array_shift($projectData);
        if (is_null($this->projectId)) {
            throw new InvalidArgumentException('Не удаётся извлечь номер проекта из публичного ключа');
        }
        $this->projectId = intval($this->projectId);

        $this->client = HttpClient::create();
    }

    /**
     * Включение и отключение тестового режима
     *
     * @param boolean $toggle `true`, если необходимо включить тестовый режим и `false`, если необходимо его выключить
     * @return void
     */
    public function toggleTestMode(bool $toggle): void
    {
        $this->testMode = $toggle;
    }

    /**
     * Изменение способа оплаты, используемого по умолчанию
     *
     * @param string $method Способ оплаты _(одна из констант, начинающихся с `PAYMENT_METHOD`)_
     * @return void
     */
    public function setDefaultPaymentMethod(string $method): void
    {
        $availableMethods = array_keys($this->availablePaymentMethods);
        if (!in_array($method, $availableMethods)) {
            throw new InvalidArgumentException('Указанный способ оплаты не поддерживается');
        }
        $this->defaultPaymentMethod = $method;
    }

    /**
     * Создание ссылки на форму оплаты
     *
     * @param float $sum Сумма платежа
     * @param string $account Идентификатор абонента _(например, email или номер заказа)_
     * @param string $description Описание заказа
     * @param string|null $paymentMethod Способ оплаты _(по умолчанию - банковские карты)_. Список поддерживаемых способов оплаты можно посмотреть [здесь](https://help.unitpay.ru/book-of-reference/payment-system-codes)
     * @param string|null $currency Валюта заказа по стандарту ISO 4217 _(по умолчанию - `RUB`)_. Список поддерживаемых валют можно посмотреть [здесь](https://help.unitpay.ru/book-of-reference/currency-codes)
     * @param string|null $locale
     * @param string|null $backUrl
     * @return string
     */
    public function form(float $sum, string $account, string $description, ?string $paymentMethod = null, ?string $currency = null, ?string $locale = null, ?string $backUrl = null): string
    {
        if ($sum <= 0) {
            throw new InvalidArgumentException('Сумма не может быть меньше или равна нулю');
        }

        if (empty($account)) {
            throw new InvalidArgumentException('Идентификатор абонента не может быть пустым');
        }

        if (empty($description)) {
            throw new InvalidArgumentException('Описание заказа не может быть пустым');
        }

        if (!empty($paymentMethod)) {
            $availableMethods = array_keys($this->availablePaymentMethods);
            if (!in_array($paymentMethod, $availableMethods)) {
                throw new InvalidArgumentException('Указанный способ оплаты не поддерживается');
            }
        }

        $params = compact('sum', 'account');
        $params['desc'] = $description;

        if (!empty($currency)) {
            if (!Currency::isSupported($currency)) {
                throw new InvalidArgumentException('Указанная валюта не поддерживается');
            }
        }
        $params['currency'] = $currency;

        if (!empty($locale)) {
            if (!in_array($locale, $this->availableLocales)) {
                throw new InvalidArgumentException('Указанный язык не поддерживается');
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
     * Проверка IP-адреса по списку IP-адресов Unitpay
     *
     * @param string $ip IP-адрес
     * @return boolean `true`, если IP корректный и `false`, если нет
     */
    public function isIpValid(string $ip): bool
    {
        if (!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            return false;
        }
        return in_array($ip, $this->ips);
    }

    /**
     * Инициализирует платёж
     *
     * @param string $method Способ оплаты
     * @param string $account Идентификатор абонента
     * @param float $sum Сумма
     * @param string $description Описание
     * @param string $ip IP-адрес
     * @param string|null $resultUrl Адрес, на который нужно будет перенаправить пользователя после оплаты
     * @param string|null $phone Номер телефона
     * @param string|null $operator Оператор
     * @return integer|null Номер платежа в системе Unitpay или `null`, если создать платёж не удалось
     * @throws InvalidArgumentException Если какой-либо из параметров указан неверно или не указан вовсе
     * @throws ApiException При ошибочном ответе API
     */
    public function initPayment(string $method, string $account, float $sum, string $description, string $ip, ?string $resultUrl = null, ?string $phone = null, ?string $operator = null): ?int
    {
        $availableMethods = array_keys($this->availablePaymentMethods);
        if (!in_array($method, $availableMethods)) {
            throw new InvalidArgumentException('Указанный способ оплаты не поддерживается');
        }
        if (empty($account)) {
            throw new InvalidArgumentException('Идентификатор абонента не может быть пустым');
        }
        if ($sum <= 0) {
            throw new InvalidArgumentException('Сумма должна быть больше нуля');
        }
        if (empty($description)) {
            throw new InvalidArgumentException('Описание не может быть пустым');
        }
        if (!filter_var($ip, FILTER_VALIDATE_IP)) {
            throw new InvalidArgumentException('Некорректный IP-адрес');
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
            $availableOperators = array_keys($this->availableOperators);
            if (!in_array($operator, $availableOperators)) {
                throw new InvalidArgumentException('Указанный оператор не поддерживается');
            }
        }

        $params['secretKey'] = $this->secretKey;
        $params['signature'] = $this->signature($params['account'], $params['desc'], $params['sum']);

        $response = $this->api('initPayment', $params);

        return $response['paymentId'];
    }

    /**
     * Проверяет запрос на корректность
     *
     * @param array $data Данные запроса
     * @return boolean `true`, если запрос корректный и `false`, если нет
     */
    public function validateRequest(array $data): bool
    {
        // TODO Метод для проверки входящих запросов от Unitpay
        return false;
    }

    /**
     * Выполнение запроса к API
     *
     * @param string $method Название метода
     * @param array $params Параметры
     * @return ResponseInterface Ответ
     * @throws ApiException При ошибочном ответе API
     */
    protected function api(string $method, array $params): ResponseInterface
    {
        if ($this->testMode) {
            $params['test'] = true;
        }

        $url = $this->baseUrl();
        $url .= '/api';

        $response = $this->client->request('GET', $url, [
            'query' => compact('method', 'params')
        ]);

        $content = $response->getContent(false);
        if (isset($content['error'])) {
            if (!isset($content['error']['message'])) {
                throw new ApiException('Неопознанная ошибка API');
            }
            throw new ApiException($content['error']['message']);
        }

        if (!isset($content['result'])) {
            throw new ApiException('В ответе API отсутствует поле "result"');
        }

        return $response;
    }

    /**
     * Вычисление подписи запроса
     *
     * @param string $account Идентификатор абонента
     * @param string $description Описание заказа
     * @param float $sum Сумма
     * @param string|null $currency Валюта _(необязательно)_
     * @return string Подпись
     */
    protected function signature(string $account, string $description, float $sum, ?string $currency = null): string
    {
        $params = compact('account', 'currency', 'description', 'sum');
        $params[] = $this->secretKey;
        $params = array_filter($params);
        $params = implode(self::SIGNATURE_DELIMITER, $params);

        $hash = hash('sha256', $params);
        return $hash;
    }

    /**
     * Получение базового адреса
     *
     * @return string
     */
    protected function baseUrl(): string
    {
        $url = 'https://' . $this->domain;
        return $url;
    }
}
