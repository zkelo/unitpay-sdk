<?php

namespace zkelo\Unitpay\Locales;

use zkelo\Unitpay\Interfaces\LocaleInterface;
use zkelo\Unitpay\Models\{
    Currency,
    Operator,
    Payment
};

/**
 * English locale
 *
 * @author Aleksandr Riabov <ar161ru@gmail.com>
 * @version 1.0.0
 */
class En extends Locale implements LocaleInterface
{
    /**
     * {@inheritDoc}
     */
    public static function messages(): array
    {
        return [
            'currency' => [
                Currency::RUB => 'Russian ruble',
                Currency::EUR => 'Euro',
                Currency::USD => 'US dollar',
                Currency::AUD => 'Australian dollar',
                Currency::AZN => 'Azerbaijani manat',
                Currency::AMD => 'Armenian dram',
                Currency::BYN => 'Belarusian ruble',
                Currency::BGN => 'Bulgarian lev',
                Currency::BRL => 'Brazilian real',
                Currency::HUF => 'Hungarian forint',
                Currency::KRW => 'Won Republic of Korea',
                Currency::HKD => 'Hong kong dollar',
                Currency::DKK => 'Danish krone',
                Currency::INR => 'Indian rupee ',
                Currency::KZT => 'Kazakhstani tenge',
                Currency::CAD => 'Canadian dollar',
                Currency::KGS => 'Kyrgyz som',
                Currency::CNY => 'Chinese yuan',
                Currency::MDL => 'Moldovan leu',
                Currency::TMT => 'New Turkmen manat',
                Currency::NOK => 'Norwegian krone',
                Currency::PLN => 'Polish zloty',
                Currency::RON => 'Romanian leu',
                Currency::SGD => 'Singapore dollar',
                Currency::TJS => 'Tajik somoni',
                Currency::TRY => 'Turkish lira',
                Currency::UZS => 'Uzbek sum',
                Currency::UAH => 'Ukrainian hryvnia',
                Currency::GBP => 'British pound sterling',
                Currency::CZK => 'Czech crown',
                Currency::SEK => 'Swedish krona',
                Currency::CHF => 'Swiss frank',
                Currency::ZAR => 'South African Rand',
                Currency::JPY => 'Japanese yen'
            ],
            'payment' => [
                'method' => [
                    Payment::METHOD_MOBILE => 'Mobile payment',
                    Payment::METHOD_CARD => 'Bank cards',
                    Payment::METHOD_WEBMONEY_Z => 'WebMoney Z-',
                    Payment::METHOD_WEBMONEY_R => 'WebMoney R-',
                    Payment::METHOD_YOOMONEY => 'ЮMoney',
                    Payment::METHOD_QIWI => 'Qiwi',
                    Payment::METHOD_PAYPAL => 'PayPal',
                    Payment::METHOD_APPLE_PAY => 'Apple Pay',
                    Payment::METHOD_SAMSUNG_PAY => 'Samsung Pay',
                    Payment::METHOD_GOOGLE_PAY => 'Google Pay'
                ]
            ],
            'operator' => [
                Operator::MTS => 'MTS',
                Operator::MEGAFON => 'Megafon',
                Operator::BEELINE => 'Beeline',
                Operator::TELE2 => 'Tele2'
            ]
        ];
    }
}
