<?php

namespace App\Controller\Api\User;

use App\Controller\Api\AbstractApiController;
use App\Entity\User\User;

class ClearDeviceApiController extends AbstractApiController
{


    public function __invoke(): void
    {
        /** @var User $user */
        $user = $this->getUser();
        $user->getDevices()->clear();
        $this->em->flush();
    }

}
