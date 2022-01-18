<?php

declare(strict_types=1);

namespace App\Command;


use App\Service\FileParser;
use App\Service\StrategyHolder;
use App\Service\StrategyProfitCalculator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ComparePeriodCommand extends Command
{
  
    protected static $defaultName = 'compare:period';

    protected function configure()
    {
        $this
            ->setName('compare:period')
            ->addOption('length', 'l', InputOption::VALUE_OPTIONAL, 'Period length', 1)
            ->addOption('start', 's', InputOption::VALUE_OPTIONAL, 'Period start')
            ->addOption('random', 'r', InputOption::VALUE_NONE, 'Random period')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $strategyHolder = new StrategyHolder();
        $parser = new FileParser();
        $profitCalculator = new StrategyProfitCalculator();
        $parser->parseCsv('tickers/' . FileParser::GAZPROM_ALL);
        $length = (int) $input->getOption('length');
        $random = (bool) $input->getOption('random');
        $profitCalculator->comparePeriod($strategyHolder->getStrategies(), $parser->getPeriod($length, $random));

        return 0;
    }
}
