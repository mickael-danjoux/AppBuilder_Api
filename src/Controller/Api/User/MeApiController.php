<?php

namespace App\Controller\Api\User;

use App\Controller\Api\AbstractApiController;
use App\ApiResources\Me;
use App\Repository\User\UserRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class MeApiController extends AbstractApiController
{


    public function __invoke( Security $security, UserRepository $userRepository): Response
    {
        $user = $security->getUser();
        if (!$user) {
            $this->createNotFoundException();
        }
        return $this->createResourceResponse(
            $userRepository->findOneById($user->getUserIdentifier()),
            ['read:User:item', 'read:User:item:private']
        );

    }

}
