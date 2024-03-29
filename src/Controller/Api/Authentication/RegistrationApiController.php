<?php

namespace App\Controller\Api\Authentication;

use App\Controller\Api\AbstractApiController;
use App\ApiResources\Registration;
use App\Entity\User\User;
use App\Services\RegistrationService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsController]
class RegistrationApiController extends AbstractApiController
{

    public function __invoke(Registration $data, Request $request, RegistrationService $registrationService)
    {
        if ($this->isValidEntity($data)) {
            $user = $registrationService->registerUser($data);
            return $this->createResourceResponse($user, ['read:User:item'], Response::HTTP_CREATED);
        }

        return $data;
    }

}
