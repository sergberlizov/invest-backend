<?php

declare(strict_types=1);

namespace App\Command;

use App\Enum\AssetTypeEnum;
use App\Model\Api\Request\CompareAssetsRequest;
use App\Service\AssetsComparator;
use App\Service\AssetsHolder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CompareAssetsCommand extends Command
{
  
    protected static $defaultName = 'assets:compare';

    public function __construct(
        string $name = null,
        private AssetsHolder $assetsHolder,
        private AssetsComparator $assetsComparator,
    ) {
        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            ->setName(self::$defaultName)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $this->assetsComparator->compare(
                $this->assetsHolder->getAsset(AssetTypeEnum::MOEX),
                $this->assetsHolder->getAsset(AssetTypeEnum::REALTY_MSC),
                CompareAssetsRequest::DEFAULT_MAX_PERIOD_DURATION,
                null //\DateTime::createFromFormat('M y', 'Jan 10')
            );
        } catch (\Exception $e) {
            echo $e->getMessage();
        }


        return 0;
    }


}
