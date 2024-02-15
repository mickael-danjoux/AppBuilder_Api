<?php

namespace App\Controller\Api\User;

use App\Controller\Api\AbstractApiController;
use App\Repository\User\UserRepository;
use App\Utils\Firebase;
use Symfony\Component\HttpFoundation\Response;

class VerificationEmailApiController extends AbstractApiController
{


    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly Firebase $firebase

    ) {
    }

    public function __invoke(string $id): Response
    {
        $user = $this->userRepository->findOneById($id);
        if (!$user) {
            throw $this->createNotFoundException();
        }
        $this->denyAccessUnlessGranted('IS_OWNER_OR_ADMIN', $user);

        $this->firebase->getFactory()->createAuth()->sendEmailVerificationLink($user->getEmail());

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}
