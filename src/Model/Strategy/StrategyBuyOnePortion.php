<?php

namespace App\Model\Strategy;

class StrategyBuyOnePortion extends StrategyBase
{
    protected $name = 'Buy 1 portion every %d month';

    protected $interval = 1;

    public function __construct(int $interval = 1)
    {
        parent::__construct();
        $this->setInterval($interval);
    }

    public function canBuy(int $counter, float $currentPrice): bool
    {
         return $counter % $this->getInterval() === 0;
    }

    public function getPortionsToBuy(float $currentPrice): int
    {
        return 1;
    }

    public function setInterval(int $interval): self
    {
        $this->interval = $interval;

        return $this;
    }

    public function getName(): string
    {
        return sprintf($this->name, $this->interval);
    }
}