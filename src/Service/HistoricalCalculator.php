<?php
declare(strict_types=1);

namespace App\Service;

use App\Exception\ValueNotFoundException;
use App\Model\HistoricalTimeline;

class HistoricalCalculator
{
    public function calculateProfit(
        HistoricalTimeline $timeline,
        \DateTime $startDate,
        int $periodDurationYears
    ): float {
        $startingPoint = $timeline->findByDate($startDate);
        if (!$startingPoint) {
            throw (new ValueNotFoundException())->setDate($startDate);
        }
        $startDate->modify('+' . $periodDurationYears . ' years');
        $endPoint = $timeline->findByDate($startDate);

        return $endPoint->getValue() / $startingPoint->getValue();
    }
}