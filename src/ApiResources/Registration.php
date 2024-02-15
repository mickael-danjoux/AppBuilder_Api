<?php

namespace App\ApiResources;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Controller\Api\Authentication\RegistrationApiController;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\UniqueEmail as AppAssert;

#[ApiResource(
    operations: [
        new Post(
            uriTemplate: '/registration',
            controller: RegistrationApiController::class,
            openapiContext: [
                'summary' => 'Register a user',
                'description' => '',
                'responses' => [
                    '201' => [
                        'description' => 'User created successfully.',
                        'content' => [
                            'application/ld+json' => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/User.jsonld',
                                ],
                            ],
                            'application/json' => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/User',
                                ],
                            ],

                        ]
                    ],
                ],
            ],
        ),
    ]),
]
class Registration
{
    #[Assert\NotBlank()]
    #[ApiProperty(example: 'John', types: ["https://schema.org/givenName"])]
    private ?string $firstName = null;

    #[Assert\NotBlank()]
    #[ApiProperty(example: 'Doe', types: ["https://schema.org/familyName"])]
    private ?string $lastName = null;

    #[ApiProperty(example: 'john.doe@ecample.com')]
    #[Assert\NotBlank()]
    #[Assert\Email]
    #[AppAssert\UniqueEmail]
    private ?string $email = null;

    #[Assert\NotBlank()]
    #[ApiProperty(example: 'qfTns8zm-dg7Ah', types: ["https://schema.org/accessCode"])]
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
