<?php

namespace App\Factory\Notification;

use App\Entity\Notification\UserNotification;
use App\Factory\User\UserFactory;
use App\Repository\Notification\UserNotificationRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<UserNotification>
 *
 * @method        UserNotification|Proxy                     create(array|callable $attributes = [])
 * @method static UserNotification|Proxy                     createOne(array $attributes = [])
 * @method static UserNotification|Proxy                     find(object|array|mixed $criteria)
 * @method static UserNotification|Proxy                     findOrCreate(array $attributes)
 * @method static UserNotification|Proxy                     first(string $sortedField = 'id')
 * @method static UserNotification|Proxy                     last(string $sortedField = 'id')
 * @method static UserNotification|Proxy                     random(array $attributes = [])
 * @method static UserNotification|Proxy                     randomOrCreate(array $attributes = [])
 * @method static UserNotificationRepository|RepositoryProxy repository()
 * @method static UserNotification[]|Proxy[]                 all()
 * @method static UserNotification[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static UserNotification[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static UserNotification[]|Proxy[]                 findBy(array $attributes)
 * @method static UserNotification[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static UserNotification[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class UserNotificationFactory extends ModelFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     */
    protected function getDefaults(): array
    {
        return [
            'devices' => [],
            'notification' => NotificationFactory::new(),
            'owner' => UserFactory::new(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(UserNotification $userNotification): void {})
        ;
    }

    protected static function getClass(): string
    {
        return UserNotification::class;
    }
}
