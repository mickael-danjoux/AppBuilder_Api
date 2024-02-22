<?php

namespace App\ApiResources;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Controller\Api\HiddenRouteController;
use App\Enum\NotificationKindEnum;
use Symfony\Component\Serializer\Attribute\Groups;


#[ApiResource(
    operations: [
        new GetCollection(controller: HiddenRouteController::class)
    ],
    formats: ['json'])]
class NotificationData
{
    #[Groups('read:NotificationData:item')]
    public NotificationKindEnum $kind;

    #[ApiProperty(example: 87)]
    #[Groups('read:NotificationData:item')]
    public ?string $id;

}
