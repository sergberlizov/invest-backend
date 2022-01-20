<?php

declare(strict_types=1);

namespace App\Controller;

use App\Enum\AssetTypeEnum;
use App\Model\Api\Request\CompareAssetsRequest;
use App\Service\Api\Converter\AssetConverter;
use App\Service\AssetsComparator;
use App\Service\AssetsHolder;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AssetController extends BaseController
{
    public function __construct(
        private AssetConverter $converter,
        private AssetsHolder $assetsHolder,
        private AssetsComparator $comparator,
        private ValidatorInterface $validator,
    ) {
        parent::__construct();
    }

    /**
     * @Rest\Get("/assets")
     */
    public function listAssets(): View
    {
        $assets = AssetTypeEnum::getValues();

        return $this->createResponse($this->converter->convertList($assets));
    }

    /**
     * @Rest\Post("/assets-compare")
     *
     * @ParamConverter(
     *      "compareAssetsRequest",
     *      converter="fos_rest.request_body",
     *      class="App\Model\Api\Request\CompareAssetsRequest"
     * )
     */
    public function compareAssets(CompareAssetsRequest $compareAssetsRequest): View
    {
        $violations = $this->validator->validate($compareAssetsRequest);
        if ($violations->count() > 0) {

            return $this->respondBadRequest($violations);
        }
        $result = $this->comparator->compare(
            $this->assetsHolder->getAsset($compareAssetsRequest->getAssetType1()),
            $this->assetsHolder->getAsset($compareAssetsRequest->getAssetType2()),
            $compareAssetsRequest->getMaxPeriodDurationYears(),
            $compareAssetsRequest->getStartDate()
        );

        return $this->createResponse($this->converter->convertComparator($result));
    }
}
