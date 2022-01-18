<?php


namespace App\Model\Strategy;


class StrategyBuyAndHold extends StrategyBase
{
    protected $name = 'Buy and hold';

    public function getPortionsToBuy(float $currentPrice): int
    {
        return 1;
    }

    public function canBuy(int $counter, float $currentPrice): bool
    {
        return $counter === 0;
    }
}