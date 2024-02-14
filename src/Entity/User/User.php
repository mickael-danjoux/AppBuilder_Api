<?php

namespace App\Entity\User;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use App\Repository\User\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
        new Patch(
            denormalizationContext: [
                'groups' => ['patch:User:item']
            ]
        ),
    ]
)]
#[UniqueEntity('email')]
class User
{
    #[ORM\Id]
    #[ORM\Column(length: 255)]
    #[Groups(['read:User:collection', 'read:User:item'])]
    private ?string $id = null;

    #[ORM\Column(length: 64)]
    #[Groups(['read:User:collection', 'read:User:item', 'patch:User:item'])]
    #[ApiProperty(example: 'John')]
    private ?string $firstName = null;

    #[ORM\Column(length: 64)]
    #[Groups(['read:User:collection', 'read:User:item', 'patch:User:item'])]
    #[ApiProperty(example: 'DOE')]
    private ?string $lastName = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\Email()]
    #[Groups(['read:User:collection', 'read:User:item', 'patch:User:item'])]
    #[ApiProperty(example: 'john.doe@ecample.com')]
    private ?string $email = null;

    /**
     * @param string|null $id
     */
    public function __construct(?string $id)
    {
        $this->id = $id;
    }


    public function getId(): ?string
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = strtolower($email);

        return $this;
    }
}
