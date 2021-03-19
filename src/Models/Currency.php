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
     * Валюта: Российский рубль
     */
    const RUB = 'RUB';

    /**
     * Валюта: Евро
     */
    const EUR = 'EUR';

    /**
     * Валюта: Доллар США
     */
    const USD = 'USD';

    /**
     * Валюта: Австралийский доллар
     */
    const AUD = 'AUD';

    /**
     * Валюта: Азербайджанский манат
     */
    const AZN = 'AZN';

    /**
     * Валюта: Армянский драм
     */
    const AMD = 'AMD';

    /**
     * Валюта: Белорусский рубль
     */
    const BYN = 'BYN';

    /**
     * Валюта: Болгарский лев
     */
    const BGN = 'BGN';

    /**
     * Валюта: Бразильский реал
     */
    const BRL = 'BRL';

    /**
     * Валюта: Венгерский форинт
     */
    const HUF = 'HUF';

    /**
     * Валюта: Вон Республики Корея
     */
    const KRW = 'KRW';

    /**
     * Валюта: Гонконгский доллар
     */
    const HKD = 'HKD';

    /**
     * Валюта: Датская крона
     */
    const DKK = 'DKK';

    /**
     * Валюта: Индийский рупий
     */
    const INR = 'INR';

    /**
     * Валюта: Казахстанский тенге
     */
    const KZT = 'KZT';

    /**
     * Валюта: Канадский доллар
     */
    const CAD = 'CAD';

    /**
     * Валюта: Киргизский сом
     */
    const KGS = 'KGS';

    /**
     * Валюта: Китайский юань
     */
    const CNY = 'CNY';

    /**
     * Валюта: Молдавский лей
     */
    const MDL = 'MDL';

    /**
     * Валюта: Новый туркменский манат
     */
    const TMT = 'TMT';

    /**
     * Валюта: Норвежский крон
     */
    const NOK = 'NOK';

    /**
     * Валюта: Польский злотый
     */
    const PLN = 'PLN';

    /**
     * Валюта: Румынский лей
     */
    const RON = 'RON';

    /**
     * Валюта: Сингапурский доллар
     */
    const SGD = 'SGD';

    /**
     * Валюта: Таджикский сомони
     */
    const TJS = 'TJS';

    /**
     * Валюта: Турецкая лира
     */
    const TRY = 'TRY';

    /**
     * Валюта: Узбекский сум
     */
    const UZS = 'UZS';

    /**
     * Валюта: Украинская гривна
     */
    const UAH = 'UAH';

    /**
     * Валюта: Фунт стерлингов Соединённого королевства
     */
    const GBP = 'GBP';

    /**
     * Валюта: Чешская крона
     */
    const CZK = 'CZK';

    /**
     * Валюта: Шведская крона
     */
    const SEK = 'SEK';

    /**
     * Валюта: Швейцарский франк
     */
    const CHF = 'CHF';

    /**
     * Валюта: Южноафриканский рэнд
     */
    const ZAR = 'ZAR';

    /**
     * Валюта: Японская йена
     */
    const JPY = 'JPY';

    /**
     * Currency code
     *
     * @var string
     */
    protected $code = '';

    /**
     * Currency name
     *
     * @var string
     */
    protected $name = '';

    /**
     * Returns currencies list
     *
     * @return array
     */
    public static function list(): array
    {
        return [
            static::RUB => 'Российский рубль',
            static::EUR => 'Евро',
            static::USD => 'Доллар США',
            static::AUD => 'Австралийский доллар',
            static::AZN => 'Азербайджанский манат',
            static::AMD => 'Армянский драм',
            static::BYN => 'Белорусский рубль',
            static::BGN => 'Болгарский лев',
            static::BRL => 'Бразильский реал',
            static::HUF => 'Венгерский форинт',
            static::KRW => 'Вон Республики Корея',
            static::HKD => 'Гонконгский доллар',
            static::DKK => 'Датская крона',
            static::INR => 'Индийский рупий ',
            static::KZT => 'Казахстанский тенге',
            static::CAD => 'Канадский доллар',
            static::KGS => 'Киргизский сом',
            static::CNY => 'Китайский юань',
            static::MDL => 'Молдавский лей',
            static::TMT => 'Новый туркменский манат',
            static::NOK => 'Норвежский крон',
            static::PLN => 'Польский злотый',
            static::RON => 'Румынский лей',
            static::SGD => 'Сингапурский доллар',
            static::TJS => 'Таджикский сомони',
            static::TRY => 'Турецкая лира',
            static::UZS => 'Узбекский сум',
            static::UAH => 'Украинская гривна',
            static::GBP => 'Фунт стерлингов Соединённого королевства',
            static::CZK => 'Чешская крона',
            static::SEK => 'Шведская крона',
            static::CHF => 'Швейцарский франк',
            static::ZAR => 'Южноафриканский рэнд',
            static::JPY => 'Японская йена'
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
        $list = array_keys(static::list());
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
}
