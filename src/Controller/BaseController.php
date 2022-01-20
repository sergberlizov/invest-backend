<?php

declare(strict_types=1);

namespace App\Controller;

use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseController extends AbstractFOSRestController
{

    /**
     * @var string
     */
    public const VERSION_V1 = '1';

    /**
     * @var string
     */
    protected $version = self::VERSION_V1;

    public function __construct()
    {
    }

    protected function createResponse($data = null, int $statusCode = Response::HTTP_OK, $version = null): View
    {
        $version = $version ?? $this->version;

        return View::create($data, $statusCode)
            ->setContext((new Context())->setVersion($version))
        ;
    }

    protected function respondBadRequest($data = null): View
    {
        return $this->createResponse($data, Response::HTTP_BAD_REQUEST);
    }

    protected function respondNotFoundRequest($data = null): View
    {
        return $this->createResponse($data, Response::HTTP_NOT_FOUND);
    }

    protected function getResponse($responseData = null): View
    {
        if (is_null($responseData)) {
            return $this->respondNotFoundRequest();
        }

        return $this->createResponse($responseData, Response::HTTP_OK);
    }
}
