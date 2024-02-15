<?php

namespace App\ApiResources;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Controller\Api\User\VerificationEmailApiController;
#[ApiResource(
    operations: [
        new Post(
            uriTemplate: '/users/{id}/email_verification',
            controller: VerificationEmailApiController::class,
            openapiContext: [
                'summary' => 'Resend the email verification',
                'description' => '',
                'requestBody' => '',
                'parameters' => [
                    'id' => [
                        'name' => 'id',
                        'in' => 'path',
                        'type' => 'string',
                        'description' => 'User id'
                    ]
                ],
                'responses' => [
                    '204' => [
                        'description' => 'Email sent',
                        'content' => [
                            '*/*' => [
                                'example' => null,
                            ]

                        ]
                    ],
                ],
            ],
            read: false
        ),
    ])
]
class EmailVerification
{

}
