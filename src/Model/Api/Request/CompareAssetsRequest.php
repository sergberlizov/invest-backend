<?php

declare(strict_types=1);

namespace App\Model\Api\Request;

use Symfony\Component\Validator\Constraints as Assert;

class CompareAssetsRequest
{
    public const DEFAULT_MAX_PERIOD_DURATION = 10;

    /**
     * @Assert\NotBlank(message="VALIDATION.DEFAULT.REQUIRED")
     * @Assert\Choice(
     *     callback = {"App\Enum\AssetTypeEnum", "getValues"},
     *     strict=true,
     *     message="VALIDATION.DEFAULT.NO_SUCH_CHOICE"
     * )
     */
    private string $assetType1;

    /**
     * @Assert\NotBlank(message="VALIDATION.DEFAULT.REQUIRED")
     * @Assert\Choice(
     *     callback = {"App\Enum\AssetTypeEnum", "getValues"},
     *     strict=true,
     *     message="VALIDATION.DEFAULT.NO_SUCH_CHOICE"
     * )
     */
    private string $assetType2;

    private ?\DateTimeInterface $startDate = null;

    private int $maxPeriodDurationYears = self::DEFAULT_MAX_PERIOD_DURATION;

    public function getMaxPeriodDurationYears(): int
    {
        return $this->maxPeriodDurationYears;
    }

    public function getAssetType1(): string
    {
        return $this->assetType1;
    }

    public function getAssetType2(): string
    {
        return $this->assetType2;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

}
