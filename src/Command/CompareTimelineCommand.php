<?php

declare(strict_types=1);

namespace App\Command;

use App\Messenger\Message\AlphaMessage;
use App\Messenger\Message\Amount;
use App\Model\Strategy\StrategyBuyEveryMonth;
use App\Service\FileParser;
use App\Service\StrategyProfitCalculator;
use PHPUnit\TextUI\XmlConfiguration\File;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class CompareTimelineCommand extends Command
{
  
    protected static $defaultName = 'compare:timeline';

    protected function configure()
    {
        $this
            ->setName('compare:timeline')
            ->addOption('length', 'l', InputOption::VALUE_OPTIONAL, 'Period length', 1)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $parser = new FileParser();
        $profitCalculator = new StrategyProfitCalculator();
        $parser->parseCsv('tickers/' . FileParser::SP500_ALL);
        $length = (int) $input->getOption('length');
        $profitCalculator->compareTimeline($parser, $length);

        return 0;
    }
}
