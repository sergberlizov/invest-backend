<?php

declare(strict_types=1);

namespace App\Model\Api\Response\Asset;

class AssetsCompareResponse
{
    public function __construct(
        private array $data,
    ) {
    }
}
