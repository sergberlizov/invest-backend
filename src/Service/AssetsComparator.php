<?php

declare(strict_types=1);

namespace App\Service;


use Symfony\Component\Serializer\Encoder\EncoderInterface;

class AssetsComparator
{
    public function __construct(
        private EncoderInterface $csvEncoder,
    ) {
    }

    public function compare(
        InvestingAsset $asset1,
        InvestingAsset $asset2,
        int $maxPeriodDurationYears,
        ?\DateTimeInterface $startDate = null
    ): array {
        $csvData = [];
        printf("%s vs %s \n\n",$asset1->getType(), $asset2->getType());
        if (!$startDate) {
            $startDate = max($asset1->getHistoricalTimeline()->getFirstDate(), $asset2->getHistoricalTimeline()->getFirstDate());
        }
        $finishDate = min($asset1->getHistoricalTimeline()->getLastDate(), $asset2->getHistoricalTimeline()->getLastDate());

        printf("Start date %s\n\n", $startDate->format('m-Y'));
        $csvData[] = [
            $asset1->getType(),
            $asset2->getType(),
            $startDate->format('m.Y')
        ];
        for ($periodDuration = 1; $periodDuration <= $maxPeriodDurationYears; $periodDuration++) {
            $startDateForPeriod = clone $startDate;
            $finishDateForPeriod = clone $finishDate;
            $finishDateForPeriod->modify('-' . $periodDuration . ' years');
            $asset1Score = $asset2Score = $periodsCount = 0;
            $totalProfit = [
                'asset1' => [],
                'asset2' => [],
            ];
            $wins = [
                'asset1' => 0,
                'asset2' => 0,
            ];
            while ($startDateForPeriod < $finishDateForPeriod) {
                $asset1Profit = $asset1->calculateProfit(clone $startDateForPeriod, $periodDuration);
                $asset2Profit = $asset2->calculateProfit(clone $startDateForPeriod, $periodDuration);
                $totalProfit['asset1'][] = $asset1Profit;
                $totalProfit['asset2'][] = $asset2Profit;
                if ($asset1Profit >= $asset2Profit) {
                    $wins['asset1']++;
                }
                if ($asset1Profit <= $asset2Profit) {
                    $wins['asset2']++;
                }
                //printf("%s - %s : %s, \n\n", $startDateForPeriod->format('m-Y'), $asset1Profit, $asset2Profit);
                $startDateForPeriod->modify('+1 month');
                $periodsCount++;
            }
            printf("Periods won (count): %d years - %s : %s, \n\n",
                $periodDuration,
                $wins['asset1'],
                $wins['asset2']
            );

           $periodScore = [
               'asset1' => round(100 * $wins['asset1'] / ($wins['asset1'] + $wins['asset2'])),
               'asset2' => round(100 * $wins['asset2'] / ($wins['asset1'] + $wins['asset2'])),
           ];


            printf("Periods won (percent): %d years - %s : %s, \n\n",
                $periodDuration,
                $periodScore['asset1'],
                $periodScore['asset2']
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
                $periodScore['asset1'],
                $periodScore['asset2'],
                $medianYield['asset1'],
                $medianYield['asset2'],
            ];
        }

        //file_put_contents('var/files/score', $this->csvEncoder->encode($csvData, 'csv'));

        return $csvData;
    }

}