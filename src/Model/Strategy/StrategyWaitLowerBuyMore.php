<?php


namespace App\Model\Strategy;


class StrategyWaitLowerBuyMore extends StrategyLowerBuyMore
{
    protected $name = 'Check every %s months. Buy only if price is lower %s * previous';

    public function canBuy(int $counter, float $currentPrice): bool
    {
        return $counter === 0 ||
            ($counter % $this->getInterval() === 0 &&
               $currentPrice < $this->portionIncreaseTreshold * $this->lastBuyingPrice
            );
    }

    public function getPortionsToBuy(float $currentPrice): int
    {
        return $this->lastPortion ? $this->lastPortion * 2 : 1;
    }
}