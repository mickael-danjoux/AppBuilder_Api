<?php

namespace App\Factory\User;

use App\Entity\User\Device;
use App\Repository\User\DeviceRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;
use function Zenstruck\Foundry\faker;

/**
 * @extends ModelFactory<Device>
 *
 * @method        Device|Proxy                     create(array|callable $attributes = [])
 * @method static Device|Proxy                     createOne(array $attributes = [])
 * @method static Device|Proxy                     find(object|array|mixed $criteria)
 * @method static Device|Proxy                     findOrCreate(array $attributes)
 * @method static Device|Proxy                     first(string $sortedField = 'id')
 * @method static Device|Proxy                     last(string $sortedField = 'id')
 * @method static Device|Proxy                     random(array $attributes = [])
 * @method static Device|Proxy                     randomOrCreate(array $attributes = [])
 * @method static DeviceRepository|RepositoryProxy repository()
 * @method static Device[]|Proxy[]                 all()
 * @method static Device[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static Device[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static Device[]|Proxy[]                 findBy(array $attributes)
 * @method static Device[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static Device[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class DeviceFactory extends ModelFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     */
    public function __construct()
    {
        parent::__construct();
    }

    protected static function getClass(): string
    {
        return Device::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     */
    protected function getDefaults(): array
    {
        return [
            'id' => self::faker()->uuid(),
            'owner' => UserFactory::new(),
            'platform' => self::faker()->randomElement(['ios', 'android']),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this// ->afterInstantiate(function(Device $device): void {})
            ;
    }
}
