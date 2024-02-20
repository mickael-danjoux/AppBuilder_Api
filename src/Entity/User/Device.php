<?php

namespace App\Entity\User;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Post;
use App\Repository\User\DeviceRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DeviceRepository::class)]
#[ApiResource(
    operations: [
        new Post(
            normalizationContext: ['groups' => ['read:Device:item']],
            denormalizationContext: ['groups' => ['create:Device:item'], 'api_allow_update'=> true]
        ) ]
)]
//#[ApiResource(
//    uriTemplate: '/users/{userId}/devices',
//    operations: [
//        new GetCollection(),
//    ],
//    uriVariables: [
//        'userId' => new Link(toProperty: 'owner', fromClass: User::class),
//    ]
//)]
//#[ApiResource(
//    uriTemplate: '/users/{userId}/devices/{id}',
//    operations: [
//        new Get(),
//        new Delete()
//    ],
//    uriVariables: [
//        'userId' => new Link(toProperty: 'owner', fromClass: User::class),
//        'id' => new Link(fromClass: Device::class),
//    ]
//)]
#[UniqueEntity('id')]
class Device
{
    #[ORM\Id]
    #[ORM\Column(length: 255)]
    #[Groups(['read:Device:collection', 'read:Device:item', 'create:Device:item'])]
    #[ApiProperty(example: 'BLar7YZ8FxVLBWXJ4z3UkNwwYMq1')]
    #[Assert\NotBlank]
    private ?string $id = null;

    #[ORM\ManyToOne(inversedBy: 'devices')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;


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


}
