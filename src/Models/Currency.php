<?php

namespace zkelo\Unitpay\Models;

use InvalidArgumentException;

/**
 * Currency model
 *
 * @author Aleksandr Riabov <ar161ru@gmail.com>
 * @version 1.0.0
 */
class Currency
{
    /**
     * Currency: Russian ruble
     */
    const RUB = 'RUB';

    /**
     * Currency: Euro
     */
    const EUR = 'EUR';

    /**
     * Currency: US dollar
     */
    const USD = 'USD';

    /**
     * Currency: Australian dollar
     */
    const AUD = 'AUD';

    /**
     * Currency: Azerbaijani manat
     */
    const AZN = 'AZN';

    /**
     * Currency: Armenian dram
     */
    const AMD = 'AMD';

    /**
     * Currency: Belarusian ruble
     */
    const BYN = 'BYN';

    /**
     * Currency: Bulgarian lev
     */
    const BGN = 'BGN';

    /**
     * Currency: Brazilian real
     */
    const BRL = 'BRL';

    /**
     * Currency: Hungarian forint
     */
    const HUF = 'HUF';

    /**
     * Currency: Won Republic of Korea
     */
    const KRW = 'KRW';

    /**
     * Currency: Hong kong dollar
     */
    const HKD = 'HKD';

    /**
     * Currency: Danish krone
     */
    const DKK = 'DKK';

    /**
     * Currency: Indian rupee
     */
    const INR = 'INR';

    /**
     * Currency: Kazakhstani tenge
     */
    const KZT = 'KZT';

    /**
     * Currency: Canadian dollar
     */
    const CAD = 'CAD';

    /**
     * Currency: Kyrgyz som
     */
    const KGS = 'KGS';

    /**
     * Currency: Chinese yuan
     */
    const CNY = 'CNY';

    /**
     * Currency: Moldovan leu
     */
    const MDL = 'MDL';

    /**
     * Currency: New Turkmen manat
     */
    const TMT = 'TMT';

    /**
     * Currency: Norwegian krone
     */
    const NOK = 'NOK';

    /**
     * Currency: Polish zloty
     */
    const PLN = 'PLN';

    /**
     * Currency: Romanian leu
     */
    const RON = 'RON';

    /**
     * Currency: Singapore dollar
     */
    const SGD = 'SGD';

    /**
     * Currency: Tajik somoni
     */
    const TJS = 'TJS';

    /**
     * Currency: Turkish lira
     */
    const TRY = 'TRY';

    /**
     * Currency: Uzbek sum
     */
    const UZS = 'UZS';

    /**
     * Currency: Ukrainian hryvnia
     */
    const UAH = 'UAH';

    /**
     * Currency: British pound sterling
     */
    const GBP = 'GBP';

    /**
     * Currency: Czech crown
     */
    const CZK = 'CZK';

    /**
     * Currency: Swedish krona
     */
    const SEK = 'SEK';

    /**
     * Currency: Swiss frank
     */
    const CHF = 'CHF';

    /**
     * Currency: South African Rand
     */
    const ZAR = 'ZAR';

    /**
     * Currency: Japanese yen
     */
    const JPY = 'JPY';

    /**
     * Currency code
     *
     * @var string
     */
    protected $code;

    /**
     * Currency name
     *
     * @var string
     */
    protected $name;

    /**
     * Returns currencies list
     *
     * @return array
     */
    public static function list(): array
    {
        return [
            static::RUB,
            static::EUR,
            static::USD,
            static::AUD,
            static::AZN,
            static::AMD,
            static::BYN,
            static::BGN,
            static::BRL,
            static::HUF,
            static::KRW,
            static::HKD,
            static::DKK,
            static::INR,
            static::KZT,
            static::CAD,
            static::KGS,
            static::CNY,
            static::MDL,
            static::TMT,
            static::NOK,
            static::PLN,
            static::RON,
            static::SGD,
            static::TJS,
            static::TRY,
            static::UZS,
            static::UAH,
            static::GBP,
            static::CZK,
            static::SEK,
            static::CHF,
            static::ZAR,
            static::JPY
        ];
    }

    /**
     * Checks if currency with following code is supported
     *
     * @param string $code Currency code
     * @return boolean `true` if currency is supported or `false` if not
     */
    public static function isSupported(string $code): bool
    {
        $list = static::list();
        return in_array($code, $list);
    }

    /**
     * Constructor
     *
     * @param string $code Currency code
     * @return void
     * @throws InvalidArgumentException If specified currency is not supported
     */
    public function __construct(string $code)
    {
        if (!static::isSupported($code)) {
            throw new InvalidArgumentException('This currency is not supported');
        }

        $list = static::list();

        $this->code = $code;
        $this->name = $list[$this->code];
    }

    /**
     * Returns currency code
     *
     * @return string
     */
    public function code(): string
    {
        return $this->code;
    }

    /**
     * Returns currency name
     *
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }
}
