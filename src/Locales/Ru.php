<?php

namespace zkelo\Unitpay\Locales;

use zkelo\Unitpay\Interfaces\LocaleInterface;
use zkelo\Unitpay\Models\Currency;

/**
 * Russian locale
 *
 * @author Aleksandr Riabov <ar161ru@gmail.com>
 * @version 1.0.0
 */
class Ru extends Locale implements LocaleInterface
{
    /**
     * {@inheritDoc}
     */
    public static function messages(): array
    {
        return [
            'currency' => [
                Currency::RUB => 'Российский рубль',
                Currency::EUR => 'Евро',
                Currency::USD => 'Доллар США',
                Currency::AUD => 'Австралийский доллар',
                Currency::AZN => 'Азербайджанский манат',
                Currency::AMD => 'Армянский драм',
                Currency::BYN => 'Белорусский рубль',
                Currency::BGN => 'Болгарский лев',
                Currency::BRL => 'Бразильский реал',
                Currency::HUF => 'Венгерский форинт',
                Currency::KRW => 'Вон Республики Корея',
                Currency::HKD => 'Гонконгский доллар',
                Currency::DKK => 'Датская крона',
                Currency::INR => 'Индийский рупий ',
                Currency::KZT => 'Казахстанский тенге',
                Currency::CAD => 'Канадский доллар',
                Currency::KGS => 'Киргизский сом',
                Currency::CNY => 'Китайский юань',
                Currency::MDL => 'Молдавский лей',
                Currency::TMT => 'Новый туркменский манат',
                Currency::NOK => 'Норвежский крон',
                Currency::PLN => 'Польский злотый',
                Currency::RON => 'Румынский лей',
                Currency::SGD => 'Сингапурский доллар',
                Currency::TJS => 'Таджикский сомони',
                Currency::TRY => 'Турецкая лира',
                Currency::UZS => 'Узбекский сум',
                Currency::UAH => 'Украинская гривна',
                Currency::GBP => 'Фунт стерлингов Соединённого королевства',
                Currency::CZK => 'Чешская крона',
                Currency::SEK => 'Шведская крона',
                Currency::CHF => 'Швейцарский франк',
                Currency::ZAR => 'Южноафриканский рэнд',
                Currency::JPY => 'Японская йена'
            ]
        ];
    }
}
