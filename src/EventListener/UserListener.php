<?php

namespace App\EventListener;

use App\Entity\User\User;
use App\Utils\Firebase\Firebase;
use Kreait\Firebase\Contract\Auth;
use Kreait\Firebase\Exception\AuthException;
use Kreait\Firebase\Exception\FirebaseException;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: 'postUpdate', method: 'postUpdate')]
#[AsEventListener(event: 'postPersist', method: 'postPersist')]
#[AsEventListener(event: 'postRemove', method: 'postRemove')]
final readonly class UserListener
{

    public function __construct(
        private Firebase $firebase,
        private LoggerInterface $logger
    ) {
    }


    public function postUpdate(User $user): void
    {
        $this->updateFirebaseUser($user);
    }

    private function updateFirebaseUser(User $user): void
    {
        try {
            $this->getAuth()->updateUser($user->getId(), [
                'displayName' => $user->getDisplayName()
            ]);
        } catch (\Exception $e) {
            $this->logger->critical('Error during Firebase synchronization: ' . $e->getMessage(), [
                'error' => $e
            ]);
        }
    }

    private function getAuth(): Auth
    {
        return $this->firebase->getFactory()->createAuth();
    }

    public function postPersist(User $user): void
    {
        try {
            if ($_SERVER['APP_ENV'] !== 'test') {
                if (!$this->getAuth()->getUser($user->getId())->emailVerified) {
                    $this->getAuth()->sendEmailVerificationLink($user->getEmail());
                }
            }
        } catch (\Exception $e) {
            $this->logger->critical('Error during sending Firebase Validation Email: ' . $e->getMessage(), [
                'error' => $e
            ]);
        }
    }

    /**
     * @throws AuthException
     * @throws FirebaseException
     */
    public function postRemove(User $user): void
    {
        try {
            $this->getAuth()->deleteUser($user->getId());
        } catch (\Exception $e) {
            $this->logger->critical('Error during removing Firebase user: ' . $e->getMessage(), [
                'error' => $e
            ]);
        }
    }

}
