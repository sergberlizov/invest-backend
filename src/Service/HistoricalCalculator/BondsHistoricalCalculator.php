<?php
declare(strict_types=1);

namespace App\Service\HistoricalCalculator;

use App\Model\HistoricalTimeline;
use App\Service\HistoricalCalculator;

class BondsHistoricalCalculator extends HistoricalCalculator
{
    /**
     * @param HistoricalTimeline\BondsHistoricalTimeline $timeline
     */
    public function calculateProfit(
        HistoricalTimeline $timeline,
        \DateTime $startDate,
        int $periodDurationYears
    ): float {
        $periods = floor($periodDurationYears / $timeline->getDuration());
        $reminder = $periodDurationYears % $timeline->getDuration();
        $result = 1;
        for ($i = 0; $i < $periods; $i++) {
            $startingPoint = $timeline->findByDate($startDate);
            $result = $result * $this->getComplexPercentResult($timeline->getDuration(), $startingPoint->getValue());
            $startDate->modify('+' . $timeline->getDuration() . ' years');
        }
        $startingPoint = $timeline->findByDate($startDate);

        return $result * $this->getComplexPercentResult($reminder, $startingPoint->getValue());
    }

    private function getComplexPercentResult(int $duration, float $percent): float
    {
        return pow((1 + $percent / 100), $duration);
    }
}