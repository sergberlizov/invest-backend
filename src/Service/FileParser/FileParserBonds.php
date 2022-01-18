<?php


namespace App\Service\FileParser;


use App\Model\Ticker;
use App\Service\FileParser;

class FileParserBonds extends FileParser
{
    protected const DELIMITER = ',';

    private const DATE_KEY = 0;

    private const PRICE_KEY = 1;

    public const RU_3Y_BONDS = 'RU_3Y_BONDS.csv';

    protected function parseRow(array $row): Ticker
    {
        return new Ticker(
            $this->parseDate('M y', $row[self::DATE_KEY]),
            $this->parsePrice($row[self::PRICE_KEY]),
            Ticker::VALUE_TYPE_PERCENT
        );
    }
}