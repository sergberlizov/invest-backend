<?php


namespace App\Model\HistoricalTimeline;


use App\Model\HistoricalTimeline;

class BondsHistoricalTimeline extends HistoricalTimeline
{
    /**
     * @var int
     */
    private $durationYears;

    public function __construct(int $durationYears)
    {
        $this->durationYears = $durationYears;
    }

    public function getDuration(): int
    {
        return $this->durationYears;
    }
}