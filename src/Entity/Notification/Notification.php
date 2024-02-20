<?php

namespace App\Entity\Notification;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\OpenApi\Model\Schema;
use App\ApiResources\NotificationData;
use App\Repository\Notification\NotificationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\Serializer\Annotation\Groups;
use OpenApi\Attributes as OA;

#[ORM\Entity(repositoryClass: NotificationRepository::class)]
class Notification
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read:Notification:item:public'])]
    #[ApiProperty(example: 45)]
    private ?int $id = null;

    #[ORM\Column(length: 64)]
    #[Groups(['read:Notification:item:public'])]
    #[ApiProperty(example: 'Welcome!')]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['read:Notification:item:public'])]
    #[ApiProperty(example: 'Hi John, Welcome to our new app!')]
    private ?string $body = null;

    #[ORM\Column(type: Types::JSON, options: ['default' => '[]'])]
    #[Groups(['read:Notification:item:public'])]
    #[ApiProperty(
        openapiContext: [
            'items' => ['$ref' => '#/components/schemas/NotificationData'],
        ]
    )]
    private array $data = [];

    #[ORM\Column]
    #[Groups(['read:Notification:item:public'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\OneToMany(mappedBy: 'notification', targetEntity: UserNotification::class)]
    private Collection $userNotifications;

    public function __construct()
    {
        $this->userNotifications = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(?string $body): static
    {
        $this->body = $body;

        return $this;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }


    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection<int, UserNotification>
     */
    public function getUserNotifications(): Collection
    {
        return $this->userNotifications;
    }

    public function addUserNotification(UserNotification $userNotification): static
    {
        if (!$this->userNotifications->contains($userNotification)) {
            $this->userNotifications->add($userNotification);
            $userNotification->setNotification($this);
        }

        return $this;
    }

    public function removeUserNotification(UserNotification $userNotification): static
    {
        if ($this->userNotifications->removeElement($userNotification)) {
            // set the owning side to null (unless already changed)
            if ($userNotification->getNotification() === $this) {
                $userNotification->setNotification(null);
            }
        }

        return $this;
    }

}
