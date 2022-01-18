<?php

declare(strict_types=1);

namespace App\Service;


use App\Exception\ValueNotFoundException;
use App\Model\HistoricalTimeline;

class InvestingAsset
{
    public function __construct(
        private string $label,
        private HistoricalTimeline $historicalTimeline,
        private HistoricalCalculator $historicalCalculator,
        private FileParser $fileParser,
        string $fileName
    ) {
        $timeline = $this->fileParser->parseCsv('tickers/' . $fileName);
        $this->getHistoricalTimeline()->setTimeline($timeline);
    }

    public function getHistoricalTimeline(): HistoricalTimeline
    {
        return $this->historicalTimeline;
    }

    public function getHistoricalCalculator(): HistoricalCalculator
    {
        return $this->historicalCalculator;
    }

    public function calculateProfit(\DateTime $startDate, int $periodDuration): float
    {
        try {
            return $this->getHistoricalCalculator()->calculateProfit(
                $this->getHistoricalTimeline(),
                $startDate,
                $periodDuration
            );
        } catch (ValueNotFoundException $e) {
            $e
                ->setAssetName($this->getLabel())
                ->setDebugInfo($this->getHistoricalTimeline()->print())
            ;
            throw $e;
        }
    }

    public function getLabel(): string
    {
        return $this->label;
    }

}