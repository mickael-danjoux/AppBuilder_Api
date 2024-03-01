<?php

namespace App\Entity\Notification;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use App\Controller\Api\Notification\ReadNotificationController;
use App\Entity\User\User;
use App\EventListener\UserNotificationListener;
use App\Repository\Notification\UserNotificationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


#[ORM\Entity(repositoryClass: UserNotificationRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(
            order: ['notification.createdAt' => 'DESC'],
        ),
        new Get(),
        new Patch(
            uriTemplate: '/user_notifications/{id}/read',
            controller: ReadNotificationController::class,
            openapiContext: [
                'summary' => 'Set notification read.'
            ],
        )
    ],
    normalizationContext: ['groups' => ['read:UserNotification:item', 'read:Notification:item:public']]
)]
#[ORM\EntityListeners([UserNotificationListener::class])]
class UserNotification
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read:UserNotification:item'])]
    #[ApiProperty(example: 24)]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['read:UserNotification:item'])]
    private ?\DateTimeImmutable $readAt = null;

    #[ORM\ManyToOne(inversedBy: 'userNotifications')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['read:UserNotification:item'])]
    private ?Notification $notification = null;

    #[ORM\ManyToOne(inversedBy: 'notifications')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;


    #[ORM\Column(type: Types::JSON, options: ['default' => '[]'])]
    private array $devices = [];


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNotification(): ?Notification
    {
        return $this->notification;
    }

    public function setNotification(?Notification $notification): static
    {
        $this->notification = $notification;

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

    public function getReadAt(): ?\DateTimeImmutable
    {
        return $this->readAt;
    }

    public function setReadAt(?\DateTimeImmutable $readAt): static
    {
        $this->readAt = $readAt;

        return $this;
    }

    public function getDevices(): array
    {
        return $this->devices;
    }

    public function setDevices(array $devices): static
    {
        $this->devices = $devices;

        return $this;
    }
}
