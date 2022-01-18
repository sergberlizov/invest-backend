<?php


namespace App\Model\Strategy;


class StrategyLowerBuyMore extends StrategyBuyOnePortion
{
    protected $name = 'Buy every %d month. If price is lower %s * prevoius, buy more';

    protected $interval = 1;

    protected $portionIncreaseTreshold = 0.95;

    public function __construct(int $interval = 1, float $treshold = 0.95)
    {
        parent::__construct($interval);
        $this->setTreshold($treshold);
    }

    public function getName(): string
    {
        return sprintf($this->name, $this->interval, $this->portionIncreaseTreshold);
    }

    public function setTreshold(float $treshold): self
    {
        $this->portionIncreaseTreshold = $treshold;

        return $this;
    }

    public function getPortionsToBuy(float $currentPrice): int
    {
        if ($currentPrice > $this->portionIncreaseTreshold * $this->lastBuyingPrice) {
            if ($this->lastPortion > 1) {
                return $this->lastPortion - 1;
            } else {
                return 1;
            }
        } else {
            return $this->lastPortion + 1;
        }
    }
}