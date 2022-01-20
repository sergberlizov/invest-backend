<?php

declare(strict_types=1);

namespace App\Service\Api\Converter;

use App\Model\Api\Response\Asset\AssetListResponse;
use App\Model\Api\Response\Asset\AssetsCompareResponse;

class AssetConverter
{
    public function convertList(array $assetsList): AssetListResponse
    {
        return new AssetListResponse($assetsList);
    }

    public function convertComparator(array $data): AssetsCompareResponse
    {
        return new AssetsCompareResponse($data);
    }
}
