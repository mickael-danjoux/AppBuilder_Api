<?php

namespace App\Tests\Api;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Notification\UserNotification;
use App\Entity\User\Device;
use App\Entity\User\User;
use App\Factory\Notification\UserNotificationFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class NotificationTest extends ApiTestCase
{
    const BASE_URL = '/api/user_notifications';

    /**
     * @group test-user-notification-crud
     * @return void
     * @throws TransportExceptionInterface
     */
    public function testNotifications()
    {
        $firebase = static::getContainer()->get('App\Utils\Firebase\FirebaseTestUser');
        $data = $firebase->getAuthenticatedTestUser();
        $results = UserNotificationFactory::createMany(3, [
            'owner' => $data['user']
        ]);

        static::createClient()->request(Request::METHOD_GET, self::BASE_URL, [
            'headers' => [
                'Authorization' => 'Bearer ' . $data['token']
            ]
        ]);

        // Test Http Response
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertMatchesResourceItemJsonSchema(UserNotification::class);

        $iri = $this->findIriBy(UserNotification::class, ['id' => $results[1]->getId()]);
        static::createClient()->request(Request::METHOD_GET, $iri, [
            'headers' => [
                'Authorization' => 'Bearer ' . $data['token']
            ]
        ]);

        // Remove to preserve Firebase ressources
        $userToRemove = static::getContainer()->get('doctrine')->getRepository(User::class)
            ->findOneById($data['user']->getId());
        $em = static::getContainer()->get('doctrine.orm.entity_manager');
        $em->remove($userToRemove);
        $em->flush();
    }
}
