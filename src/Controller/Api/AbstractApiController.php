<?php

namespace App\Controller\Api;

use ApiPlatform\Serializer\SerializerContextBuilderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Service\Attribute\Required;

class AbstractApiController extends AbstractController
{
    protected ValidatorInterface $validator;

    protected SerializerInterface $serializer;

    public function isValidEntity(object $entity): bool
    {
        $errors = $this->validator->validate($entity);
        return !count($errors) > 0;
    }

    public function createResourceResponse(object $data, ?array $groups = null): Response
    {
        $accept = $this->getAcceptData();
        $data = $this->serializer->serialize($data, $accept['serialization'], $groups);
        $response = new Response($data);
        $response->headers->set('Content-Type', $accept['response']);
        return $response;
    }

    protected function getAcceptData(): array
    {
        $received = Request::createFromGlobals()->headers->get('accept', 'application/ld+json');
        if ($received !== 'application/json') {
            $received = 'application/ld+json';
        }
        return [
            'response' => $received,
            'serialization' => $received === 'application/ld+json' ? 'jsonld' : 'json'
        ];
    }

    #[Required]
    public function setValidator(ValidatorInterface $validator): void
    {
        $this->validator = $validator;
    }

    #[Required]
    public function setSerializer(SerializerInterface $serializer): void
    {
        $this->serializer = $serializer;
    }

}
