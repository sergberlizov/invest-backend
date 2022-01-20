<?php

declare(strict_types=1);

namespace App\Service;

class AssetsHolder
{
    /**
     * @var InvestingAsset[]
     */
    private array $assets = [];

    public function addAsset(InvestingAsset $asset): self
    {
        $this->assets[$asset->getLabel()] = $asset;

        return $this;
    }

    public function getAsset(string $type): InvestingAsset
    {
        return $this->assets[$type];
    }

}