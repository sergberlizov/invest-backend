<?php


namespace App\Model\Strategy\Result;


class TimelineResult
{
    public const ABSOLUTE_PROFIT = 'AbsoluteProfit';

    public const RELATIVE_PROFIT = 'RelativeProfit';

    public const WINS = 'Wins';

    public const LOSS = 'Loss';

    private $absoluteProfitWins = 0;

    private $relativeProfitWins = 0;

    private $absoluteProfitLoss = 0;

    private $relativeProfitLoss = 0;

    /**
     * @var PeriodResult[]
     */
    private $periodResults;

    public function addPeriodResult(PeriodResult $periodResult)
    {
        $this->periodResults[] = $periodResult;
    }

    public function addAbsoluteProfitWin(): void
    {
        $this->absoluteProfitWins++;
    }

    public function addRelativeProfitWin(): void
    {
        $this->relativeProfitWins++;
    }

    public function addAbsoluteProfitLoss(): void
    {
        $this->absoluteProfitLoss++;
    }

    public function addRelativeProfitLoss(): void
    {
        $this->relativeProfitLoss++;
    }

    public function getAbsoluteProfitWins(): int
    {
        return $this->absoluteProfitWins;
    }

    public function getRelativeProfitWins(): int
    {
        return $this->relativeProfitWins;
    }

    public function getAbsoluteProfitLoss(): int
    {
        return $this->absoluteProfitLoss;
    }

    public function getRelativeProfitLoss(): int
    {
        return $this->relativeProfitLoss;
    }

    public function getAverageProfit(string $profitType): float
    {
        $method = 'get' . $profitType;
        $profit = 0;
        foreach ($this->periodResults as $periodResult) {
             $profit += $periodResult->$method();
        }

        return round($profit / $this->getPeriodsCount(), 2);
    }

    public function getBestPeriod(string $profitType): PeriodResult
    {
        $method = 'get' . $profitType;
        $bestPeriod = current($this->periodResults);
        foreach ($this->periodResults as $periodResult) {
            if ($periodResult->$method() > $bestPeriod->$method()) {
                $bestPeriod = $periodResult;
            }
        }

        return $bestPeriod;
    }

    public function getWorstPeriod(string $profitType): PeriodResult
    {
        $method = 'get' . $profitType;
        $worstPeriod = current($this->periodResults);
        foreach ($this->periodResults as $periodResult) {
            if ($periodResult->$method() < $worstPeriod->$method()) {
                $worstPeriod = $periodResult;
            }
        }

        return $worstPeriod;
    }

    public function getPeriodsCount(): int
    {
        return count($this->periodResults);
    }

    public function getPrintableResult(string $profitType, string $ratingType): string
    {
        $method = 'get' . $profitType. $ratingType;
        return sprintf("%s - %s - %s - %s\n\n",
            round(100 * $this->$method() / $this->getPeriodsCount()),
            $this->getAverageProfit($profitType),
            $this->getBestPeriod($profitType),
            $this->getWorstPeriod($profitType)
        );
    }
}