<?php

namespace App\DTO;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Controller\Api\Authentication\RegistrationController;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\UniqueEmail as AppAssert;
#[
    ApiResource(

        operations: [
            new Post(
                uriTemplate: '/registration',
                controller: RegistrationController::class,
                openapiContext: [
                    'summary' => 'Register a user',
                    'description' => ''
                ],
                name: 'api_registration',
            ),
        ]),

]
class RegistrationDto
{
    #[ApiProperty(example: 'John')]
    #[Assert\NotBlank()]
    private ?string $firstName = null;

    #[ApiProperty(example: 'Doe')]
    #[Assert\NotBlank()]
    private ?string $lastName = null;

    #[ApiProperty(example: 'john.doe@ecample.com')]
    #[Assert\NotBlank()]
    #[Assert\Email]
    #[AppAssert\UniqueEmail]
    private ?string $email = null;

    #[ApiProperty(example: 'qfTns8zm - dg7Ah')]
    #[Assert\NotBlank()]
    private ?string $password = null;


    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = ucfirst($firstName);
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = strtoupper($lastName);
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }

    public function getDisplayName(): string
    {
        return $this->firstName . ' ' . $this->lastName;
    }

}
