<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Controller\Api\CreateBooking;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
        new Post(
            denormalizationContext: [
                'groups' => ['create:User:item']
            ]
        ),
        new Patch(
            denormalizationContext: [
                'groups' => ['patch:User:item']
            ]
        ),
    ]
)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read:User:collection', 'read:User:item'])]
    private ?int $id = null;

    #[ORM\Column(length: 64)]
    #[Groups(['read:User:collection', 'read:User:item', 'create:User:item', 'patch:User:item'])]
    #[ApiProperty(example: 'John')]
    private ?string $firstName = null;

    #[ORM\Column(length: 64)]
    #[Groups(['read:User:collection', 'read:User:item', 'create:User:item', 'patch:User:item'])]
    #[ApiProperty(example: 'DOE')]
    private ?string $lastName = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = ucfirst($firstName);

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = strtoupper($lastName);

        return $this;
    }
}
