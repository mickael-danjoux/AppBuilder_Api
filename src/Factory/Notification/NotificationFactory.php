<?php

namespace App\Factory\Notification;

use App\Entity\Notification\Notification;
use App\Repository\Notification\NotificationRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Notification>
 *
 * @method        Notification|Proxy                     create(array|callable $attributes = [])
 * @method static Notification|Proxy                     createOne(array $attributes = [])
 * @method static Notification|Proxy                     find(object|array|mixed $criteria)
 * @method static Notification|Proxy                     findOrCreate(array $attributes)
 * @method static Notification|Proxy                     first(string $sortedField = 'id')
 * @method static Notification|Proxy                     last(string $sortedField = 'id')
 * @method static Notification|Proxy                     random(array $attributes = [])
 * @method static Notification|Proxy                     randomOrCreate(array $attributes = [])
 * @method static NotificationRepository|RepositoryProxy repository()
 * @method static Notification[]|Proxy[]                 all()
 * @method static Notification[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static Notification[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static Notification[]|Proxy[]                 findBy(array $attributes)
 * @method static Notification[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static Notification[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class NotificationFactory extends ModelFactory
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
            'createdAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'data' => [
                'kind' => 'WELCOME',
                'id' => null
            ],
            'title' => self::faker()->text(32),
            'body' => self::faker()->text(46),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Notification $notification): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Notification::class;
    }
}
