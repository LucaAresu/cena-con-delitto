<?php

namespace App\Factory;

use CenaConDelitto\Shared\Entity\Dinner;
use CenaConDelitto\Shared\Repository\DinnerRepository;
use Symfony\Component\Uid\Uuid;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Dinner>
 *
 * @method        Dinner|Proxy create(array|callable $attributes = [])
 * @method static Dinner|Proxy createOne(array $attributes = [])
 * @method static Dinner|Proxy find(object|array|mixed $criteria)
 * @method static Dinner|Proxy findOrCreate(array $attributes)
 * @method static Dinner|Proxy first(string $sortedField = 'id')
 * @method static Dinner|Proxy last(string $sortedField = 'id')
 * @method static Dinner|Proxy random(array $attributes = [])
 * @method static Dinner|Proxy randomOrCreate(array $attributes = [])
 * @method static DinnerRepository|RepositoryProxy repository()
 * @method static Dinner[]|Proxy[] all()
 * @method static Dinner[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Dinner[]|Proxy[] createSequence(array|callable $sequence)
 * @method static Dinner[]|Proxy[] findBy(array $attributes)
 * @method static Dinner[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static Dinner[]|Proxy[] randomSet(int $number, array $attributes = [])
 *
 */
final class DinnerFactory extends ModelFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function getDefaults(): array
    {
        return [
            'name' => self::faker()->text(127),
            'uuid' => Uuid::fromString(self::faker()->uuid()),
            'active' => false,
            'created_at' => \DateTimeImmutable::createFromMutable(self::faker()->datetime()),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this// ->afterInstantiate(function(Dinner $dinner): void {})
            ;
    }

    protected static function getClass(): string
    {
        return Dinner::class;
    }
}
