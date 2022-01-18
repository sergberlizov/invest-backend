<?php

declare(strict_types=1);

namespace App\Service\FileParser;


use App\Model\Ticker;
use App\Service\FileParser;

class FileParserMoex extends FileParser
{
    protected const DELIMITER = ';';

    private const DATE_KEY = 0;

    private const PRICE_KEY = 2;

    protected function parseRow(array $row): Ticker
    {
        return new Ticker(
            $this->parseDate('Ymd', $row[self::DATE_KEY]),
            $this->parsePrice($row[self::PRICE_KEY]),
            Ticker::VALUE_TYPE_VALUE
        );
    }
}