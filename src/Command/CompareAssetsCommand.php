<?php

declare(strict_types=1);

namespace App\Command;

use App\Enum\AssetTypeEnum;
use App\Service\AssetsComparator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CompareAssetsCommand extends Command
{
  
    protected static $defaultName = 'assets:compare';
    private AssetsComparator $assetsComparator;

    public function __construct(string $name = null, AssetsComparator $assetsComparator)
    {
        parent::__construct($name);

        $this->assetsComparator = $assetsComparator;
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
                $this->assetsComparator->getAsset(AssetTypeEnum::MOEX),
                $this->assetsComparator->getAsset(AssetTypeEnum::REALTY_MSC),
                null //\DateTime::createFromFormat('M y', 'Jan 10')
            );
        } catch (\Exception $e) {
            echo $e->getMessage();
        }


        return 0;
    }


}
