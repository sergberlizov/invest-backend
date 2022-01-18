<?php


namespace App\Model\Strategy;


use App\Model\Strategy\Result\PeriodResult;
use App\Model\Strategy\Result\TimelineResult;

abstract class StrategyBase
{
    protected $name;

    protected $interval;

    protected $lastPortion = 0;

    protected $lastBuyingPrice = 0;

    protected $previousPrice = 0;

    private $totalPortions = 0;

    private $totalAmount = 0;

    private $averagePrice = 0;

    private $timelineResult;

    public function __construct()
    {
        $this->timelineResult = new TimelineResult();
    }

    public function setPreviousPrice(float $previousPrice): self
    {
        $this->previousPrice = $previousPrice;

        return $this;
    }

    public function getPreviousPrice(): float
    {
        return $this->previousPrice;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getInterval(): int
    {
        return $this->interval;
    }

    abstract public function getPortionsToBuy(float $currentPrice): int;

    abstract public function canBuy(int $counter, float $currentPrice): bool;

    public function tryToBuy(int $intervalNumber, float $price, bool $verbose = false): bool
    {
        $canBuy = $this->canBuy($intervalNumber, $price);
        if ($canBuy) {
            $this->buy($price, $this->getPortionsToBuy($price), $verbose);
        } elseif ($verbose) {
            $this->debug($price, 0);
        }
        $this->setPreviousPrice($price);

        return $canBuy;
    }

    public function buy(float $price, int $portions, bool $verbose = false)
    {
        $this->lastPortion = $portions;
        $this->lastBuyingPrice = $price;
        $this->totalPortions += $portions;
        $this->totalAmount += $price * $portions;
        $this->averagePrice = round($this->totalAmount / $this->totalPortions, 2);

        if ($verbose) {
            $this->debug($price, $portions);
        }
    }

    public function debug($currentMarketPrice, $portionsBought)
    {
        printf("%s - %s - %s - %s - %s\n",
            $currentMarketPrice, $portionsBought, $this->averagePrice,
            $this->getRelativeProfit($currentMarketPrice),
            $this->getAbsoluteProfit($currentMarketPrice)
        );
    }

    public function getRelativeProfit(float $currentMarketPrice): float
    {
        return round($currentMarketPrice / $this->averagePrice, 2);
    }

    public function getAbsoluteProfit(float $currentMarketPrice): float
    {
        return $this->totalPortions * ($currentMarketPrice - $this->averagePrice);
    }

    public function getAveragePrice(): float
    {
        return $this->averagePrice;
    }

    public function finalizePeriod()
    {
        $this->averagePrice = 0;
        $this->totalAmount = 0;
        $this->totalPortions = 0;
        $this->lastPortion = 0;
        $this->lastBuyingPrice = 0;
    }

    public function getTimelineResult(): TimelineResult
    {
        return $this->timelineResult;
    }

    public function addPeriodResult(PeriodResult $periodResult)
    {
        $this->getTimelineResult()->addPeriodResult($periodResult);
    }

    public function getTimelineAverageProfit(string $profitType)
    {
        return $this->getTimelineResult()->getAverageProfit($profitType);
    }

    public function getTimelineBestPeriod(string $profitType): PeriodResult
    {
        return $this->getTimelineResult()->getBestPeriod($profitType);
    }

    public function getTimelineWorstPeriod(string $profitType): PeriodResult
    {
        return $this->getTimelineResult()->getWorstPeriod($profitType);
    }

    public function getTimelineAbsoluteProfitWins(): int
    {
        return $this->getTimelineResult()->getAbsoluteProfitWins();
    }

    public function getTimelineRelativeProfitWins(): int
    {
        return $this->getTimelineResult()->getRelativeProfitWins();
    }

    public function getTimelineAbsoluteProfitLoss(): int
    {
        return $this->getTimelineResult()->getAbsoluteProfitLoss();
    }

    public function getTimelineRelativeProfitLoss(): int
    {
        return $this->getTimelineResult()->getRelativeProfitLoss();
    }

    public function getPeriodsCount(): int
    {
        return $this->getTimelineResult()->getPeriodsCount();
    }

    public function printTimelineResult(string $profitType, string $ratingType)
    {
        printf("%s - %s\n\n",
            $this->getName(),
            $this->getTimelineResult()->getPrintableResult($profitType, $ratingType)
        );
    }
}