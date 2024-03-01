<?php

namespace App\Utils\Firebase;

use App\Entity\User\User;
use App\Factory\User\UserFactory;
use App\Repository\User\UserRepository;
use App\Services\RegistrationService;
use Zenstruck\Foundry\Test\Factories;

readonly class FirebaseTestUser
{
    use Factories;

    public function __construct(
        private RegistrationService $registrationService,
        private Firebase $firebase,
        private UserRepository $userRepository
    ) {
    }

    public function getAuthToken(): ?string
    {
        // Ensure user exist in FireBase
        $this->getOrCreateUser();
        $userData = $this->getUserData();
        $result = $this->firebase->getFactory()->createAuth()->signInWithEmailAndPassword(
            $userData['email'],
            $userData['password']
        );
        return $result->idToken();
    }

    public function getOrCreateUser(): User
    {
        $userData = $this->getUserData();
        $user = $this->userRepository->findOneByEmail($userData['email']);
        $firebase = $this->firebase->getFactory()->createAuth();
        if (!$user) {
            try {
                $firebaseUser = $firebase->getUserByEmail($userData['email']);
            } catch (\Exception $e) {
                $firebaseUser = null;
            }
            if (!$firebaseUser) {
                $firebaseUser = $firebase->createUser([
                    'email' => $userData['email'],
                    'displayName' => $userData['firstName'] . ' ' . $userData['lastName'],
                    'password' => $userData['password']
                ]);
            }
            $user = UserFactory::createOne([
                'id' => $firebaseUser->uid,
                'lastName' => $userData['lastName'],
                'firstName' => $userData['firstName'],
                'email' => $userData['email']
            ])->object();
        }
        return $user;
    }

    private function getUserData(): array
    {
        return [
            'email' => 'user1-sware@mailsac.com',
            'firstName' => 'John',
            'lastName' => 'Doe',
            'password' => 'azertyuiop'
        ];
    }

}
