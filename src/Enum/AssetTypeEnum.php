<?php

declare(strict_types=1);

namespace App\Enum;


class AssetTypeEnum
{
    public const USD_RUB = 'usd-rub';
    public const MOEX = 'moex';
    public const MOEX_DIV = 'moex-div';
    public const RU_3Y_BONDS = 'ru-3y-bonds';
    public const PRICES = 'prices';
    public const REALTY_MSC = 'realty-msc';

    public static function getValues()
    {
        return [
            self::USD_RUB,
            self::MOEX,
            self::MOEX_DIV,
            self::RU_3Y_BONDS,
            self::PRICES,
            self::REALTY_MSC,
        ];
    }
}