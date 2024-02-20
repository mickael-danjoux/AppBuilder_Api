<?php

namespace App\ApiResources;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Controller\Api\HiddenRouteController;
use Symfony\Component\Serializer\Attribute\Groups;


#[ApiResource(
    operations: [
        new GetCollection(controller: HiddenRouteController::class)
    ],
    formats: ['json'])]
class NotificationData
{
    #[ApiProperty(example: 'REGISTRATION_SUCCESS')]
    #[Groups('read:NotificationData:item')]
    public string $kind;

    #[ApiProperty(example: 87)]
    #[Groups('read:NotificationData:item')]
    public ?string $id;

}
