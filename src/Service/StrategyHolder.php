<?php


namespace App\Service;


use App\Model\Strategy\Result\TimelineResult;
use App\Model\Strategy\StrategyBase;
use App\Model\Strategy\StrategyBuyAndHold;
use App\Model\Strategy\StrategyBuyIfPreviousHigher;
use App\Model\Strategy\StrategyBuyOnePortion;
use App\Model\Strategy\StrategyHigherBuyOne;
use App\Model\Strategy\StrategyHigherBuyOneRememberLowest;
use App\Model\Strategy\StrategyLowerBuyMore;
use App\Model\Strategy\StrategyWaitLowerBuyMore;

class StrategyHolder
{
    /**
     * @return StrategyBase[]
     */
    public function getStrategies(): array
    {
        $strategies = [
            new StrategyBuyAndHold(),
            new StrategyWaitLowerBuyMore(1, 0.95),
            new StrategyWaitLowerBuyMore(2, 0.95),
            new StrategyWaitLowerBuyMore(3, 0.95),
            new StrategyWaitLowerBuyMore(4, 0.95),
            new StrategyWaitLowerBuyMore(6, 0.95),
            new StrategyBuyOnePortion(1),
            new StrategyBuyOnePortion(2),
            new StrategyBuyOnePortion(3),
            new StrategyBuyOnePortion(4),
            new StrategyBuyOnePortion(5),
            new StrategyBuyOnePortion(6),
            new StrategyLowerBuyMore(1, 0.95),
            new StrategyLowerBuyMore(2, 0.95),
            new StrategyLowerBuyMore(3, 0.95),
            new StrategyLowerBuyMore(4, 0.95),
            new StrategyLowerBuyMore(5, 0.95),
            new StrategyLowerBuyMore(6, 0.95),
            new StrategyHigherBuyOne(1, 0.95),
            new StrategyBuyIfPreviousHigher(1, 0.95),
            new StrategyHigherBuyOneRememberLowest(1, 0.95),
        ];

        return $strategies;
    }
}