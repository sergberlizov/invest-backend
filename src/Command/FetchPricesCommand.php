<?php

declare(strict_types=1);

namespace App\Command;

use App\Client\IrnClient;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Serializer\Encoder\EncoderInterface;

class FetchPricesCommand extends Command
{
    protected static $defaultName = 'prices:fetch';
    private IrnClient $client;
    private EncoderInterface $encoder;

    public function __construct(string $name = null, IrnClient $client, EncoderInterface $encoder)
    {
        parent::__construct($name);

        $this->client = $client;
        $this->encoder = $encoder;
    }

    protected function configure()
    {
        $this
            ->setName(self::$defaultName)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $startDate = new \DateTime('2000-01-01');
        $endDate = new \DateTime('2021-01-01');
        $csvData = [];
        while ($startDate < $endDate) {
            $response = $this->client
                ->get(sprintf(IrnClient::URL, $startDate->modify('+1 month')->format('d.m.Y')))
                ->getBody()
                ->getContents()
            ;
            preg_match_all('/<span class="display-block space-small-top"><strong>(.*?)<\/strong>/si', $response, $matches);

            if (!empty($matches[1][1])) {
                $elem = [
                    $startDate->format('M y'),
                    str_replace(' ', '', $matches[1][1])
                ];
                $csvData[] = $elem;
                var_dump($elem);
                file_put_contents('tickers/Realty_Index_Msc_00_21.csv', $this->encoder->encode($csvData, 'csv'));
            }

            sleep(8);
        }

        return 0;
    }


}
