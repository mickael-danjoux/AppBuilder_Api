<?php

namespace App\Tests\Api;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\User\User;
use App\Factory\Notification\UserNotificationFactory;
use App\Factory\User\DeviceFactory;
use App\Factory\User\UserFactory;
use App\Utils\Firebase\FirebaseTestUser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Zenstruck\Foundry\Test\Factories;

class UserTest extends ApiTestCase
{
    use Factories;

    const BASE_URL = '/api/users';


    /**
     * @group test-user-crud
     * @return void
     * @throws TransportExceptionInterface
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function testRegisterUser(): void
    {
        $faker = UserFactory::faker();
        $response = static::createClient()->request(Request::METHOD_POST, '/api/registration', [
            'json' => [
                'email' => $faker->email(),
                'firstName' => $faker->firstName(),
                'lastName' => $faker->lastName(),
                'password' => $faker->password()
            ]
        ]);

        // Test HTTP Response
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json');
        $this->assertMatchesResourceItemJsonSchema(User::class);

        // Test DB updates
        $userToRemove = static::getContainer()->get('doctrine')->getRepository(User::class)
            ->findOneById($response->toArray()['id']);
        $this->assertNotNull($userToRemove);

        // Remove to preserve Firebase ressources
        $em = static::getContainer()->get('doctrine.orm.entity_manager');
        $em->remove($userToRemove);
        $em->flush();

    }

    /**
     * @group test-user-crud
     * @throws TransportExceptionInterface
     */
    public function testPatchUserWithinAuth(): void
    {
        $faker = UserFactory::faker();
        $lastName = $faker->lastName();
        $firstName = $faker->firstName();
        $this->patch(true, [
            'lastName' => $lastName,
            'firstName' => $firstName
        ]);

        // Test HTTP Response
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        // Test DB Updates
        /** @var FirebaseTestUser $firebaseUser */
        $firebaseUser = self::getContainer()->get('App\Utils\Firebase\FirebaseTestUser');
        /** @var User $user */
        $user = static::getContainer()->get('doctrine')->getRepository(User::class)
            ->findOneById($firebaseUser->getOrCreateUser()->getId());

        $this->assertEquals(strtoupper($lastName) , $user->getLastName());
        $this->assertEquals($firstName, $user->getFirstName());

    }

    /**
     * @param bool $auth
     * @param array $data
     * @throws TransportExceptionInterface
     */
    private function patch(bool $auth = true, array $data = []): void
    {

        /** @var FirebaseTestUser $firebaseUser */
        $firebaseUser = self::getContainer()->get('App\Utils\Firebase\FirebaseTestUser');
        $iri = $this->findIriBy(User::class, ['id' => $firebaseUser->getOrCreateUser()->getId()]);

        $headers = [
            'Content-Type' => 'application/merge-patch+json',
        ];
        if ($auth) {
            $headers['Authorization'] = 'Bearer ' . $firebaseUser->getAuthToken();
        }
        static::createClient()->request(Request::METHOD_PATCH, $iri, [
            'json' => $data,
            'headers' => $headers
        ]);

    }


    /**
     * @group test-user-crud
     * @throws TransportExceptionInterface
     */
    public function testPatchUserWithoutAuth(): void
    {
        $this->patch(false);
        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @group test-user-crud
     * @throws TransportExceptionInterface
     */
    public function testRemoveUser(): void
    {


        $firebase = static::getContainer()->get('App\Utils\Firebase\FirebaseTestUser');
        $data = $firebase->getAuthenticatedTestUser();

        DeviceFactory::createMany(3,['owner' => $data['user']]);
        UserNotificationFactory::createMany(3,[
            'owner' => $data['user']
        ]);

        $id = $data['user']->getId();

        $headers = [
            'Content-Type' => 'application/merge-patch+json',
            'Authorization' => 'Bearer ' . $data['token']
        ];
        $iri = $this->findIriBy(User::class, ['id' => $id]);

        static::createClient()->request(Request::METHOD_DELETE, $iri, [
            'headers' => $headers
        ]);

        // Test HTTP Response
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);

        // Test DB Updates
        $this->assertNull(
            static::getContainer()->get('doctrine')->getRepository(User::class)->findOneById($id)
        );

    }


}
