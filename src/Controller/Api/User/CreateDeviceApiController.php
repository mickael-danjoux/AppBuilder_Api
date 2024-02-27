<?php

namespace App\Controller\Api\User;

use App\Controller\Api\AbstractApiController;
use App\Entity\User\Device;
use Symfony\Bundle\SecurityBundle\Security;

class CreateDeviceApiController extends AbstractApiController
{
    public function __invoke(Device $data, Security $security): Device
    {
        $data->setOwner($security->getUser());
        return $data;
    }


}
