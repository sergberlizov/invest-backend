<?php

declare(strict_types=1);

namespace App\Model\Api\Response\Asset;

use JMS\Serializer\Annotation as Serializer;

class AssetsCompareItemResponse
{
    private string $name;

    /**
     * @var AssetsCompareValueResponse[]
     *
     * @Serializer\Type("array<App\Model\Api\Response\Asset\AssetsCompareValueResponse>")
     */
    private array $series = [];

    public function __construct(string $name, array $series)
    {
        $this->name = $name;
        $this->series = $series;
    }
}
