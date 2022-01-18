<?php

declare(strict_types=1);

namespace App\Model;

class HistoricalTimeline
{
    /**
     * @var Ticker[]
     */
    protected array $timeline;

    public function setTimeline(array $timeline)
    {
        $this->timeline = $timeline;
    }

    public function findByDate(\DateTime $date): ?Ticker
    {
        if ($date < $this->getFirstDate()) {
            return $this->getFirstElement();
        }
        if ($date > $this->getLastDate()) {
            return $this->getLastElement();
        }
        foreach ($this->timeline as $ticker) {
            if ($ticker->getDate()->format('Y-m') === $date->format('Y-m')) {
                return $ticker;
            }
        }

        return null;
    }

    public function getFirstElement(): Ticker
    {
        return $this->timeline[0];
    }

    public function getLastElement(): Ticker
    {
        return $this->timeline[count($this->timeline) - 1];
    }

    public function getFirstDate(): \DateTime
    {
        return clone $this->getFirstElement()->getDate();
    }

    public function getLastDate(): \DateTime
    {
        return clone $this->getLastElement()->getDate();
    }

    public function print(): string
    {
        $data = '';
        foreach ($this->timeline as $ticker) {
            $data .= $ticker->getDate()->format('Y-m-d') . PHP_EOL;
        }

        return $data;
    }
}