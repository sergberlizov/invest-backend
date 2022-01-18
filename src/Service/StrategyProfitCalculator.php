<?php


namespace App\Service;


use App\Model\Strategy\Result\PeriodResult;
use App\Model\Strategy\Result\TimelineResult;
use App\Model\Strategy\StrategyBase;
use App\Model\Ticker;

class StrategyProfitCalculator
{
    public function compareTimeline(FileParser $file, int $periodLengthYears)
    {
        $strategyHolder = new StrategyHolder();
        $strategies = $strategyHolder->getStrategies();
        foreach ($file->getPeriods($periodLengthYears) as $period) {
            $periodStart = $period[0];
            /** @var Ticker $ticker */
            foreach ($period as $i => $ticker) {
                foreach ($strategies as $strategy) {
                    $strategy->tryToBuy($i, $ticker->getValue());
                }
            }
            $currentMarketPrice = $ticker->getValue();
            $this->sortBy($strategies, $currentMarketPrice, 'getAbsoluteProfit');
            $strategies[0]->getTimelineResult()->addAbsoluteProfitWin();
            $strategies[count($strategies) - 1]->getTimelineResult()->addAbsoluteProfitLoss();
            $this->sortBy($strategies, $currentMarketPrice, 'getRelativeProfit');
            $strategies[0]->getTimelineResult()->addRelativeProfitWin();
            $strategies[count($strategies) - 1]->getTimelineResult()->addRelativeProfitLoss();
            foreach ($strategies as $strategy) {
                $strategyPeriodResult = (new PeriodResult())
                    ->setStartDate($periodStart->getDate())
                    ->setEndDate($ticker->getDate())
                    ->setRelativeProfit($strategy->getRelativeProfit($ticker->getValue()))
                    ->setAbsoluteProfit($strategy->getAbsoluteProfit($ticker->getValue()))
                ;
                $strategy->addPeriodResult($strategyPeriodResult);
                $strategy->finalizePeriod();
            }
        }

        printf("\n\n**ABSOLUTE PROFIT**\n\n");
        $this->sortBy($strategies, $currentMarketPrice, 'getTimelineAbsoluteProfitWins');
        foreach (array_slice($strategies, 0, 3) as $strategy) {
            $strategy->printTimelineResult(TimelineResult::ABSOLUTE_PROFIT, TimelineResult::WINS);
        }

        printf("\n\n**RELATIVE PROFIT**\n\n");
        $this->sortBy($strategies, $currentMarketPrice, 'getTimelineRelativeProfitWins');
        foreach (array_slice($strategies, 0, 3) as $strategy) {
            $strategy->printTimelineResult(TimelineResult::RELATIVE_PROFIT, TimelineResult::WINS);
        }

        printf("\n\n**ABSOLUTE PROFIT LOOSERS**\n\n");
        $this->sortBy($strategies, $currentMarketPrice, 'getTimelineAbsoluteProfitLoss');
        foreach (array_slice($strategies, 0, 3) as $strategy) {
            $strategy->printTimelineResult(TimelineResult::ABSOLUTE_PROFIT, TimelineResult::LOSS);
        }

        printf("\n\n**RELATIVE PROFIT LOOSERS**\n\n");
        $this->sortBy($strategies, $currentMarketPrice, 'getTimelineRelativeProfitLoss');
        foreach (array_slice($strategies, 0, 3) as $strategy) {
            $strategy->printTimelineResult(TimelineResult::RELATIVE_PROFIT, TimelineResult::LOSS);
        }
    }

    /**
     * @param StrategyBase[] $strategies
     */
    public function comparePeriod(array $strategies, array $period)
    {
        /** @var Ticker $ticker */
        foreach ($period as $i => $ticker) {
            foreach ($strategies as $strategy) {
                $strategy->tryToBuy($i, $ticker->getValue());
            }
        }

        $currentMarketPrice = $ticker->getValue();
        $this->sortBy($strategies, $currentMarketPrice, 'getRelativeProfit');
        printf("\n**PERIOD**\n");
        printf("\nSTART: %s\n", $period[0]->getDate());
        printf("\nEND: %s\n", $ticker->getDate());
        printf("\n**PROFIT IN PERCENT**\n\n");
        foreach ($strategies as $strategy) {
            printf("%s - %s\n",
                $strategy->getName(),
                $strategy->getRelativeProfit($currentMarketPrice)
            );
        }
        printf("\n\n**PROFIT IN AMOUNT**\n\n");
        $this->sortBy($strategies, $currentMarketPrice, 'getAbsoluteProfit');
        foreach ($strategies as $strategy) {
            printf("%s - %s\n",
                $strategy->getName(),
                $strategy->getAbsoluteProfit($currentMarketPrice)
            );
        }
    }

    public function debugPeriod(StrategyBase $strategy, array $period)
    {
        foreach ($period as $i => $ticker) {
            $strategy->tryToBuy($i, $ticker->getValue(), verbose: true);
        }
    }

    /**
     * @param StrategyBase[] $strategies
     */
    private function sortBy(array &$strategies, float $currentMarketPrice, string $methodName)
    {
        usort($strategies,
            function (StrategyBase $strategy1, StrategyBase $strategy2) use ($currentMarketPrice, $methodName)
        {
            return $strategy1->$methodName($currentMarketPrice)
                <=> $strategy2->$methodName($currentMarketPrice)
                ;

        });
    }

    /**
     * @param StrategyBase[] $strategies
     */
    private function sortByAbsoluteProfit(array &$strategies, float $currentMarketPrice)
    {
        usort($strategies, function (StrategyBase $strategy1, StrategyBase $strategy2) use ($currentMarketPrice)
        {
            return $strategy1->getAbsoluteProfit($currentMarketPrice)
                <=> $strategy2->getAbsoluteProfit($currentMarketPrice)
                ;

        });
    }

}