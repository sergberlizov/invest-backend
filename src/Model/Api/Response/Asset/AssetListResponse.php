<?php

declare(strict_types=1);

namespace App\Model\Api\Response\Asset;

use JMS\Serializer\Annotation as Serializer;

class AssetListResponse
{
    /**
     * @var AssetResponse[]
     *
     * @Serializer\Inline
     * @Serializer\Type("array<App\Model\Api\Response\Asset\AssetResponse>")
     */
    private array $data = [];

    public function __construct(
        array $assets
    ) {
        $this->data = array_map(
            fn ($type) => new AssetResponse($type),
            $assets
        );
    }
}
