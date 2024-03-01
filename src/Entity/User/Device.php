<?php

namespace App\Entity\User;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Controller\Api\User\ClearDeviceApiController;
use App\Controller\Api\User\CreateDeviceApiController;
use App\Repository\User\DeviceRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DeviceRepository::class)]
#[ApiResource(
    operations: [
        new Post(
            controller: CreateDeviceApiController::class,
            openapiContext: ['summary' => 'Create Device (current user)'],
            normalizationContext: ['groups' => ['read:Device:item']],
            denormalizationContext: ['groups' => ['create:Device:item']],
            security: 'is_granted("ROLE_USER")'
        ),
        new GetCollection(
            openapiContext: ['summary' => 'Retrieves the collection of Device resources. (current user)'],
            paginationEnabled: false, normalizationContext: ['groups' => ['read:Device:item']],
            security: 'is_granted("ROLE_USER")'
        ),
        new Get(
            openapiContext: ['summary' => 'Retrieves a Device resource.'],
            normalizationContext: ['groups' => ['read:Device:item']],
            security: 'is_granted("ROLE_USER")'
        ),
        new Delete(security: 'is_granted("ROLE_ADMIN") or object.getOwner() == user'),
        new Delete(
            uriTemplate: '/devices_clear',
            controller: ClearDeviceApiController::class,
            security: 'is_granted("ROLE_USER")'
        ),
    ])]
#[UniqueEntity('id')]
class Device
{
    #[ORM\Id]
    #[ORM\Column(length: 255)]
    #[Groups(['read:Device:item', 'create:Device:item'])]
    #[ApiProperty(example: 'BLar7YZ8FxVLBWXJ4z3UkNwwYMq1')]
    #[Assert\NotBlank]
    private ?string $id = null;

    #[ORM\ManyToOne(inversedBy: 'devices')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;

    #[ORM\Column(length: 16)]
    #[ApiProperty(example: 'ios')]
    #[Groups(['read:Device:item', 'create:Device:item'])]
    private ?string $platform = null;


    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string|null $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;
        return $this;
    }

    public function getPlatform(): ?string
    {
        return $this->platform;
    }

    public function setPlatform(string $platform): static
    {
        $this->platform = $platform;
        return $this;
    }


}
