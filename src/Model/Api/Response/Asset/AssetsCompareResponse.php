<?php

declare(strict_types=1);

namespace App\Model\Api\Response\Asset;

use JMS\Serializer\Annotation as Serializer;

class AssetsCompareResponse
{
    /**
     * @var AssetsCompareItemResponse[]
     *
     * @Serializer\Inline
     */
    private array $data = [];

    public function __construct(array $data)
    {
        $this->data = $data;
    }
}
