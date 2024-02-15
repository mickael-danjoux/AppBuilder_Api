<?php

namespace App\Entity\User;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use App\EventListener\UserListener;
use App\Repository\User\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ApiResource(
    operations: [
        new Get(
            normalizationContext: ['groups' => ['read:User:item']],
            security: 'is_granted("ROLE_ADMIN") or  object == user'
        ),
        new GetCollection(
            normalizationContext: ['groups' => ['read:User:item']],
            security: 'is_granted("ROLE_ADMIN")'
        ),
        new Patch(
            normalizationContext: ['groups' => ['read:User:item']],
            denormalizationContext: ['groups' => ['patch:User:item']],
            security: 'is_granted("ROLE_ADMIN") or object == user'
        ),
    ],
)]
#[UniqueEntity('email')]
#[ORM\HasLifecycleCallbacks]
#[ORM\EntityListeners([UserListener::class])]
class User implements UserInterface
{
    #[ORM\Id]
    #[ORM\Column(length: 255)]
    #[Groups(['read:User:collection', 'read:User:item'])]
    #[ApiProperty(example: 'BLar7YZ8FxVLBWXJ4z3UkNwwYMq1')]
    protected ?string $id = null;

    #[ORM\Column(length: 64)]
    #[Groups(['read:User:collection', 'read:User:item', 'patch:User:item'])]
    #[ApiProperty(example: 'John', types: ["https://schema.org/givenName"])]
    protected ?string $firstName = null;

    #[ORM\Column(length: 64)]
    #[Groups(['read:User:collection', 'read:User:item', 'patch:User:item'])]
    #[ApiProperty(example: 'Doe', types: ["https://schema.org/familyName"])]
    protected ?string $lastName = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\Email()]
    #[Groups(['read:User:collection', 'read:User:item', 'patch:User:item'])]
    #[ApiProperty(example: 'john.doe@ecample.com')]
    protected ?string $email = null;

    #[ORM\Column]
    #[Groups(['read:User:collection', 'read:User:item'])]
    #[ApiProperty(example: '["ROLE_USER"]')]
    protected array $roles = [];

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, options: ['default' => '2024-01-01'])]
    #[Groups(['read:User:collection', 'read:User:item'])]
    protected \DateTimeImmutable $createdAt;

    #[ORM\Column(nullable: true)]
    #[Groups(['read:User:collection', 'read:User:item'])]
    protected ?\DateTime $updatedAt = null;


    /**
     * @param string|null $id
     */
    public function __construct(?string $id)
    {
        $this->id = $id;
        $this->createdAt = new \DateTimeImmutable();

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = strtolower($email);

        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): User
    {
        $this->roles = $roles;
        return $this;
    }

    public function eraseCredentials(): void
    {
        // not needed for apps that do not check user passwords
    }

    public function getUserIdentifier(): string
    {
        return $this->id;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    #[ORM\PreUpdate]
    public function preUpdate(): void
    {
        $this->updatedAt = new \DateTime();
    }

    public function getDisplayName(): string
    {
        return $this->firstName . ' ' . $this->getLastName();
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
