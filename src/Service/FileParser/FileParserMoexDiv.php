<?php


namespace App\Service\FileParser;


use App\Model\Ticker;
use App\Service\FileParser;

class FileParserMoexDiv extends FileParser
{
    protected const DELIMITER = ';';

    private const DATE_KEY = 2;

    private const PRICE_KEY = 3;

    protected function parseRow(array $row): Ticker
    {
        return new Ticker(
            $this->parseDate('Y-m-d', $row[self::DATE_KEY]),
            $this->parsePrice($row[self::PRICE_KEY]),
            Ticker::VALUE_TYPE_VALUE
        );
    }
}