<?php


namespace App\Model;


class Ticker
{
    public const VALUE_TYPE_VALUE = 'VALUE';

    public const VALUE_TYPE_PERCENT = 'PERCENT';

    private $date;

    private $value;

    private $valueType ;

    public function __construct($date, float $value, string $valueType = self::VALUE_TYPE_VALUE)
    {
        $this->date = $date;
        $this->value = $value;
        $this->valueType = $valueType;
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function getDate()
    {
        return $this->date;
    }
}