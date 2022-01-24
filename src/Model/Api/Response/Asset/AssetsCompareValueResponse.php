<?php

declare(strict_types=1);

namespace App\Model\Api\Response\Asset;

class AssetsCompareValueResponse
{
    public function __construct(
        private string $name,
        private float $value,
    ) {
    }
}
