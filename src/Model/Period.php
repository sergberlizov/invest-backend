<?php


namespace App\Model;


class Period
{

    private $startDate;

    private $endDate;

    private $marketResult;

    public function setMarketResult(float $marketResult): self
    {
        $this->marketResult = $marketResult;

        return $this;
    }

    public function getMarketResult(): float
    {
        return $this->marketResult;
    }

    public function getEndDate()
    {
        return $this->endDate;
    }

    public function getStartDate()
    {
        return $this->startDate;
    }

    public function setStartDate($date): self
    {
        $this->startDate = $date;
        return $this;
    }

    public function setEndDate($date): self
    {
        $this->endDate = $date;
        return $this;
    }
}