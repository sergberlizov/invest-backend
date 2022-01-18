<?php


namespace App\Model\Strategy\Result;


use App\Model\Period;

class PeriodResult extends Period
{
    private $relativeProfit;

    private $absoluteProfit;

    public function getAbsoluteProfit(): float
    {
        return $this->absoluteProfit;
    }

    public function setAbsoluteProfit(float $absoluteProfit): self
    {
        $this->absoluteProfit = $absoluteProfit;

        return $this;
    }

    public function setRelativeProfit(float $relativeProfit): self
    {
        $this->relativeProfit = $relativeProfit;

        return $this;
    }

    public function getRelativeProfit(): float
    {
        return $this->relativeProfit;
    }

    public function __toString()
    {
       return sprintf('[%s:%s - %s - %s]',
           $this->getStartDate(), $this->getEndDate(), $this->getRelativeProfit(), $this->getAbsoluteProfit()
       );
    }
}