<?php

namespace App\ApiResources;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use App\Controller\Api\User\MeApiController;

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
                                    '$ref' => '#/components/schemas/User.jsonld',
                                ],
                            ],
                            'application/json' => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/User',
                                ],
                            ],

                        ]
                    ],
                ],
            ],
            read: false,
        ),
    ])
]
class Me
{

}
