<?php


namespace App\Model\Strategy;


class StrategyHigherBuyOneRememberLowest extends StrategyHigherBuyOne
{
    protected $name = 'Buy every %d month. If price is lower %s * least, buy more';

    private $lowestBuyingPrice = 0;

    private $lowestPricePortion = 1;

    public function buy($price, $portions, $verbose = false)
    {
        parent::buy($price, $portions, $verbose);
        if (!$this->lowestBuyingPrice || $price < $this->lowestBuyingPrice * $this->portionIncreaseTreshold ) {
            $this->lowestBuyingPrice = $price;
        }
    }

    public function getPortionsToBuy(float $currentPrice): int
    {
        if ($currentPrice >= $this->portionIncreaseTreshold * $this->lowestBuyingPrice) {
            return 1;
        } else {
            $this->lowestPricePortion += 1;
            return $this->lowestPricePortion;
        }
    }
}