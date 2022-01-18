<?php


namespace App\Service;


use App\Model\Ticker;

class FileParser
{
    public const LUKOIL_ALL = 'LKOH_000101_201231.csv';

    public const LUKOIL_FAST_GROW = 'LKOH_17_19.csv';

    public const GAZPROM_ALL = 'GAZP_000101_201231.csv';

    public const MOEX_ALL = 'IMOEX_790101_201231.csv';

    public const SP500_ALL = 'SANDP-500_010101_201231.csv';

    public const REALTY_MSC = 'Realty_Index_Msc_00_21.csv';

    protected const DELIMITER = ';';

    private const DATE_KEY = 0;

    private const OPEN_PRICE_KEY = 2;

    /**
     * @var Ticker[]
     */
    private array $fullTimeline = [];

    public function parseCsv(string $path): array
    {
        var_dump(file_get_contents($path));
        if (($handle = fopen($path, 'r')) !== FALSE) {
            while (($row = fgetcsv($handle, 1000, static::DELIMITER)) !== FALSE) {

                $this->fullTimeline[] = $this->parseRow($row);
            }
            fclose($handle);
        }

        return $this->fullTimeline;
    }

    protected function parseRow(array $row): Ticker
    {
        return new Ticker($row[self::DATE_KEY], (float) $row[self::OPEN_PRICE_KEY]);
    }

    public function getPeriod(int $lengthInYears, bool $random = false)
    {
        $lengthInMonths = $lengthInYears * 12;
        $offset = 0;
        if ($random) {
            $offset = array_rand($this->fullTimeline);
        }
        $period = array_slice($this->fullTimeline, $offset, $lengthInMonths);
        if (count($period) >= $lengthInMonths) {
            return $period;
        }

        return $this->getPeriod($lengthInYears, $random);
    }

    public function getPeriods(int $lengthInYears): \Generator
    {
        $lengthInMonths = $lengthInYears * 12;
        foreach ($this->fullTimeline as $i => $ticker) {
            $period = array_slice($this->fullTimeline, $i, $lengthInMonths);
            if (count($period) >= $lengthInMonths) {
                yield $period;
            }
        }
    }

    protected function parseDate(string $format, string $dateString): \DateTime
    {
        $dateString = preg_replace("/[^A-Za-z0-9\s-]/", '', $dateString);
        $date = \DateTime::createFromFormat($format, $dateString);
        if (!$date) {
            throw new \Exception('invalid date ' . $dateString);
        }
        if (false !== strpos($dateString, 'Feb') && (int) $date->format('m') === 3) {
            $date->modify('-2 days');
        }
        $date->setDate($date->format('Y'), $date->format('m'), 1);

        return $date;
    }

    protected function parsePrice(string $priceString): float
    {
        $price = (float) $priceString;
        if ($price < 0) {
            throw new \Exception('Price below zero');
        }

        return $price;
    }
}