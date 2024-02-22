<?php

namespace App\ApiResources;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use App\Controller\Api\User\MeApiController;
use App\Entity\User\User;

#[ApiResource(
    operations: [
        new Get(
            uriTemplate: '/me',
            controller: MeApiController::class,
            openapiContext: [
                'summary' => 'Retrieve current user',
                'description' => '',
                'responses' => [
                    '200' => [
                        'description' => 'The user signed in',
                        'content' => [
                            'application/ld+json' => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/Me.jsonld-read.User.item_read.User.item.private',
                                ],
                            ],
                            'application/json' => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/Me-read.User.item_read.User.item.private',
                                ],
                            ],
                        ]
                    ],
                ],
            ],
            normalizationContext: ['groups' => ['read:User:item', 'read:User:item:private']],
            read: false
        ),
    ])
]
class Me extends User
{

}
