<?php

namespace App\Services;

use App\DTO\RegistrationDto;
use App\Entity\User\User;
use App\Utils\Firebase;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Kreait\Firebase\Exception\AuthException;
use Kreait\Firebase\Exception\FirebaseException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;

readonly class RegistrationService
{


    public function __construct(private Firebase $firebase, private EntityManagerInterface $em)
    {
    }

    /**
     * @throws FirebaseException
     * @throws AuthException
     */
    public function registerUser(RegistrationDto $dto): User
    {
        $firebaseAuth = $this->firebase->getFactory()->createAuth();

        // Create User in firebase
        try {
            $createdUser = $firebaseAuth->createUser([
                'email' => $dto->getEmail(),
                'displayName' => $dto->getDisplayName(),
                'password' => $dto->getPassword()
            ]);

        } catch (\Exception $exception) {

            throw new HttpException(Response::HTTP_BAD_REQUEST);
        }

        // Create User in App DB
        try{
            $user = new User($createdUser->uid);
            $user->setFirstName($dto->getFirstName())
                ->setLastName($dto->getLastName())
                ->setEmail($dto->getEmail());

            $this->em->persist($user);
            $this->em->flush();

            return $user;
        }catch (\Exception $exception){
            // Rollback Firebase if error
            $firebaseAuth->deleteUser($createdUser->uid);
            throw $exception;
        }
    }
}
