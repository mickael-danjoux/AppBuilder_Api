<?php

namespace App\Tests\Api;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\User\Device;
use App\Factory\User\DeviceFactory;
use App\Utils\Firebase\FirebaseTestUser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Zenstruck\Foundry\Test\Factories;

class DeviceCrudTest extends ApiTestCase
{
    use Factories;

    const BASE_URL = '/api/devices';

    /**
     * @group test-device-crud
     * @return void
     * @throws TransportExceptionInterface
     */
    public function testAddDevice()
    {
        $firebaseUser = self::getContainer()->get('App\Utils\Firebase\FirebaseTestUser');
        static::createClient()->request(Request::METHOD_POST, self::BASE_URL, [
            'json' => [
                "id" => DeviceFactory::faker()->uuid(),
                "platform" => DeviceFactory::faker()->randomElement(['ios', 'android'])
            ],
            'headers' => [
                'Authorization' => 'Bearer ' . $firebaseUser->getAuthToken()
            ]
        ]);

        // Test Http Response
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertMatchesResourceItemJsonSchema(Device::class);
    }

    /**
     * @group test-device-crud
     * @return void
     * @throws TransportExceptionInterface
     */
    public function testRemoveDevice()
    {
        /** @var FirebaseTestUser $firebaseUser */
        $firebaseUser = self::getContainer()->get('App\Utils\Firebase\FirebaseTestUser');

        $user = $firebaseUser->getOrCreateUser();
        $device = DeviceFactory::createOne(['owner' => $user]);
        // Save Id for remove
        $id = $device->getId();
        $iri = $this->findIriBy(Device::class, ['id' => $device->getId()]);

        static::createClient()->request(Request::METHOD_DELETE, $iri, [
            'headers' => [
                'Authorization' => 'Bearer ' . $firebaseUser->getAuthToken()
            ]
        ]);

        // Test Http Response
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);

        // Test DB Updates
        $this->assertNull(
            static::getContainer()->get('doctrine')->getRepository(Device::class)->findOneById($id)
        );
    }

    /**
     * @group test-device-crud
     * @return void
     * @throws TransportExceptionInterface
     */
    public function testGetCollection()
    {
        /** @var FirebaseTestUser $firebaseUser */
        $firebaseUser = self::getContainer()->get('App\Utils\Firebase\FirebaseTestUser');
        $user = $firebaseUser->getOrCreateUser();
        DeviceFactory::createMany(3, [
            'owner' => $user
        ]);
        static::createClient()->request(Request::METHOD_GET, self::BASE_URL, [
            'headers' => [
                'Authorization' => 'Bearer ' . $firebaseUser->getAuthToken()
            ]
        ]);

        // Test Http Response
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertMatchesResourceCollectionJsonSchema(Device::class);
    }

    /**
     * @group test-device-crud
     * @return void
     * @throws TransportExceptionInterface
     */
    public function testGetOneDevice()
    {
        /** @var FirebaseTestUser $firebaseUser */
        $firebaseUser = self::getContainer()->get('App\Utils\Firebase\FirebaseTestUser');
        $user = $firebaseUser->getOrCreateUser();
        $device = DeviceFactory::createOne([
            'owner' => $user
        ]);
        $iri = $this->findIriBy(Device::class, ['id' => $device->getId()]);

        static::createClient()->request(Request::METHOD_GET, $iri, [
            'headers' => [
                'Authorization' => 'Bearer ' . $firebaseUser->getAuthToken()
            ]
        ]);

        // Test Http Response
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertMatchesResourceItemJsonSchema(Device::class);
    }

    /**
     * @group test-device-crud
     * @return void
     * @throws TransportExceptionInterface
     */
    public function testClearAllDevices()
    {
        /** @var FirebaseTestUser $firebaseUser */
        $firebaseUser = self::getContainer()->get('App\Utils\Firebase\FirebaseTestUser');
        $user = $firebaseUser->getOrCreateUser();
        DeviceFactory::createMany(3, [
            'owner' => $user
        ]);
        static::createClient()->request(Request::METHOD_DELETE, '/api/devices_clear', [
            'headers' => [
                'Authorization' => 'Bearer ' . $firebaseUser->getAuthToken()
            ]
        ]);

        // Test Http Response
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);

        // Test DB Updates
        $this->assertEmpty(
            static::getContainer()->get('doctrine')->getRepository(Device::class)->findByOwner($user)
        );

    }

}
