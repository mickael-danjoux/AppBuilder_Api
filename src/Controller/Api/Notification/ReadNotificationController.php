<?php

namespace App\Controller\Api\Notification;

use App\Controller\Api\AbstractApiController;
use App\Entity\Notification\UserNotification;

class ReadNotificationController extends AbstractApiController
{
    public function __invoke(UserNotification $data): UserNotification
    {
        $data->setReadAt(new \DateTimeImmutable());
        return $data;
    }

}
