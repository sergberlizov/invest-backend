<?php

declare(strict_types=1);

namespace App\Model\Api\Response\Asset;

class AssetResponse
{
    public function __construct(
        private string $type,
    ) {
    }
}
