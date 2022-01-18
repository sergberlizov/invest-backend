<?php

declare(strict_types=1);

namespace App\Command;



use App\Model\Strategy\StrategyHigherBuyOneRememberLowest;
use App\Service\FileParser;
use App\Service\StrategyProfitCalculator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class StrategyDebugCommand extends Command
{
  
    protected static $defaultName = 'strategy:debug';

    protected function configure()
    {
        $this
            ->setName('strategy:debug')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $parser = new FileParser();
        $strategy = new StrategyHigherBuyOneRememberLowest(1, 0.95);
        $profitCalculator = new StrategyProfitCalculator();
        $parser->parseCsv('tickers/' . FileParser::MOEX_ALL);
        $profitCalculator->debugPeriod($strategy, $parser->getPeriod(2));

        return 0;
    }
}
