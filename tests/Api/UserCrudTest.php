<?php

namespace Api;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\User\User;
use App\Utils\Firebase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * These tests are dependent because there is no Firebase test base.
 */
class UserCrudTest extends ApiTestCase
{

    public function testRegisterUser(): void
    {
        $response = static::createClient()->request(Request::METHOD_POST, '/api/registration', [
            'json' => $this->getRegistrationData()
        ]);
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json');

    }
    public function testPatchUserWithinAuth(): void
    {
        $this->patch();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testPatchUserWithoutAuth(): void
    {
        $this->patch(false);
        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public function testRemoveUser(): void
    {
        /** @var User $user */
        $user = static::getContainer()->get('doctrine')->getRepository(User::class)->findOneBy(['email' => $this->getRegistrationData()['email']]);
        $headers = [
            'Content-Type' => 'application/merge-patch+json',
            'Authorization' => 'Bearer ' . $this->getAuthToken()
        ];
        $response = static::createClient()->request(Request::METHOD_DELETE, '/api/users/' . $user->getId(), [
            'headers' => $headers
        ]);
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);

    }

    private function patch(bool $auth = true): void
    {
        /** @var User $user */
        $user = static::getContainer()->get('doctrine')->getRepository(User::class)->findOneBy(['email' => $this->getRegistrationData()['email']]);
        $headers = [
            'Content-Type' => 'application/merge-patch+json',
        ];
        if ($auth) {
            $headers['Authorization'] = 'Bearer ' . $this->getAuthToken();
        }
        $response = static::createClient()->request(Request::METHOD_PATCH, '/api/users/' . $user->getId(), [
            'json' => [
                'firstName' => 'Johny',
                'lastName' => 'begood'
            ],
            'headers' => $headers
        ]);
    }

    private function getRegistrationData(): array
    {
        return [
            'email' => 'johndoe-sware@mailsac.fr',
            'firstName' => 'john',
            'lastName' => 'doe',
            'password' => 'password'
        ];
    }

    private function getAuthToken(): string
    {
        $user = $this->getRegistrationData();
        /** @var Firebase $firebase */
        $firebase = static::getContainer()->get('App\Utils\Firebase');
        $result = $firebase->getFactory()->createAuth()->signInWithEmailAndPassword($user['email'], $user['password']);
        return $result->idToken();
    }


}
