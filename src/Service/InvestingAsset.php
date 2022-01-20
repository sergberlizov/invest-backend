<?php

declare(strict_types=1);

namespace App\Service;


use App\Exception\ValueNotFoundException;
use App\Model\HistoricalTimeline;

class InvestingAsset
{
    public function __construct(
        private string $type,
        private HistoricalTimeline $historicalTimeline,
        private HistoricalCalculator $historicalCalculator,
        private FileParser $fileParser,
    ) {
        $timeline = $this->fileParser->parseCsv( $type . '.csv');
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

    public function getType(): string
    {
        return $this->type;
    }

    public function getLabel(): string
    {
        return $this->type;
    }

}