<?php

namespace App\EventListener;

use App\Entity\Notification\UserNotification;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: 'postRemove', method: 'postRemove')]
final readonly class UserNotificationListener
{

    public function __construct(private EntityManagerInterface $em)
    {
    }

    /**
     * Remove Notification Object if it has only one $user
     * @param UserNotification $userNotification
     * @return void
     */
    public function postRemove(UserNotification $userNotification): void
    {
        $notification = $userNotification->getNotification();
        if ($notification->getUserNotifications()->count() === 0) {
            $this->em->remove($notification);
            $this->em->flush();
        }
    }
}
