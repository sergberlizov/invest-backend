<?php


namespace App\Model\Strategy;


class StrategyBuyIfPreviousHigher extends StrategyLowerBuyMore
{
    protected $name = 'Check every %s months. Buy only if price is lower %s * previous market price';

    public function canBuy(int $counter, float $currentPrice): bool
    {
        return $counter === 0 ||
            ($counter % $this->getInterval() === 0 &&
               $currentPrice < $this->portionIncreaseTreshold * $this->getPreviousPrice()
            );
    }

    public function getPortionsToBuy(float $currentPrice): int
    {
        return $this->lastPortion ? 2 : 1;
    }
}