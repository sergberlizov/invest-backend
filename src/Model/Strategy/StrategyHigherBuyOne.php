<?php


namespace App\Model\Strategy;


class StrategyHigherBuyOne extends StrategyLowerBuyMore
{
    protected $name = 'Buy every %d month. If price is higher %s * previous, buy only one';

    public function getPortionsToBuy(float $currentPrice): int
    {
        if ($currentPrice > $this->portionIncreaseTreshold * $this->lastBuyingPrice) {
            return 1;
        } else {
            return $this->lastPortion + 1;
        }
    }
}