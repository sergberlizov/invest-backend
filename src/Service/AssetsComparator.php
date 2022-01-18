<?php

declare(strict_types=1);

namespace App\Service;


use Symfony\Component\Serializer\Encoder\EncoderInterface;

class AssetsComparator
{
    private EncoderInterface $csvEncoder;
    private array $assets = [];

    public function __construct(EncoderInterface $csvEncoder)
    {
        $this->csvEncoder = $csvEncoder;
    }

    public function addAsset(string $id, InvestingAsset $asset)
    {
        $this->assets[$id] = $asset;
    }

    public function getAsset(string $id): InvestingAsset
    {
        return $this->assets[$id];
    }

    public function compare(
        InvestingAsset $asset1,
        InvestingAsset $asset2,
        ?\DateTime $startDate = null
    ): void {
        $csvData = [];
        printf("%s vs %s \n\n",$asset1->getLabel(), $asset2->getLabel());
        if (!$startDate) {
            $startDate = max($asset1->getHistoricalTimeline()->getFirstDate(), $asset2->getHistoricalTimeline()->getFirstDate());
        }
        $maxDate = min($asset1->getHistoricalTimeline()->getLastDate(), $asset2->getHistoricalTimeline()->getLastDate());

        printf("Start date %s\n\n", $startDate->format('m-Y'));
        $csvData[] = [
            $asset1->getLabel(),
            $asset2->getLabel(),
            $startDate->format('m.Y')
        ];
        for ($periodDuration = 1; $periodDuration <= 10; $periodDuration++) {
            $startDateForPeriod = clone $startDate;
            $maxDateForPeriod = clone $maxDate;
            $maxDateForPeriod->modify('-' . $periodDuration . ' years');
            $asset1Score = $asset2Score = $periodsCount = 0;
            $totalProfit = [
                'asset1' => [],
                'asset2' => [],
            ];
            while ($startDateForPeriod < $maxDateForPeriod) {
                $asset1Profit = $asset1->calculateProfit(clone $startDateForPeriod, $periodDuration);
                $asset2Profit = $asset2->calculateProfit(clone $startDateForPeriod, $periodDuration);
                $totalProfit['asset1'][] = $asset1Profit;
                $totalProfit['asset2'][] = $asset2Profit;
                if ($asset1Profit >= $asset2Profit) {
                    $asset1Score++;
                }
                if ($asset1Profit <= $asset2Profit) {
                    $asset2Score++;
                }
                //printf("%s - %s : %s, \n\n", $startDateForPeriod->format('m-Y'), $asset1Profit, $asset2Profit);
                $startDateForPeriod->modify('+1 month');
                $periodsCount++;
            }
            printf("Periods won (count): %d years - %s : %s, \n\n",
                $periodDuration,
                $asset1Score,
                $asset2Score
            );




           $periodsScore = [
               'asset1' => round(100 * $asset1Score / ($asset1Score + $asset2Score)),
               'asset2' => round(100 * $asset2Score / ($asset1Score + $asset2Score)),
           ];


            printf("Periods won (percent): %d years - %s : %s, \n\n",
                $periodDuration,
                $periodsScore['asset1'],
                $periodsScore['asset2']
            );
            sort($totalProfit['asset1']);
            sort($totalProfit['asset2']);

            $medianYield = [
                'asset1' => round((100 * $totalProfit['asset1'][floor($periodsCount / 2)] - 100)),
                'asset2' => round((100 * $totalProfit['asset2'][floor($periodsCount / 2)] - 100)),
            ];
            printf("median yield: %d years - %s : %s, \n\n",
                $periodDuration,
                $medianYield['asset1'],
                $medianYield['asset2']
            );

            $csvData[] = [
                $periodDuration,
                $periodsScore['asset1'],
                $periodsScore['asset2'],
                $medianYield['asset1'],
                $medianYield['asset2'],
            ];
        }

        file_put_contents('var/files/score', $this->csvEncoder->encode($csvData, 'csv'));

    }

}