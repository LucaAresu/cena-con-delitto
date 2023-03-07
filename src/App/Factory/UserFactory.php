<?php

declare(strict_types=1);

namespace App\Factory;

use CenaConDelitto\Shared\Entity\User;
use CenaConDelitto\Shared\Enum\UserRoles;
use CenaConDelitto\Shared\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Uid\Uuid;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<User>
 *
 * @method        User|Proxy                     create(array|callable $attributes = [])
 * @method static User|Proxy                     createOne(array $attributes = [])
 * @method static User|Proxy                     find(object|array|mixed $criteria)
 * @method static User|Proxy                     findOrCreate(array $attributes)
 * @method static User|Proxy                     first(string $sortedField = 'id')
 * @method static User|Proxy                     last(string $sortedField = 'id')
 * @method static User|Proxy                     random(array $attributes = [])
 * @method static User|Proxy                     randomOrCreate(array $attributes = [])
 * @method static UserRepository|RepositoryProxy repository()
 * @method static User[]|Proxy[]                 all()
 * @method static User[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static User[]|Proxy[]                 createSequence(array|callable $sequence)
 * @method static User[]|Proxy[]                 findBy(array $attributes)
 * @method static User[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static User[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class UserFactory extends ModelFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    public function __construct(private UserPasswordHasherInterface $userPasswordHasher)
    {
        parent::__construct();
    }

    protected function getDefaults(): array
    {
        return [
            'password' => self::faker()->password(),
            'roles' => [],
            'username' => self::faker()->userName(),
            'uuid' => Uuid::v7(),
            'is_guest' => false,
        ];
    }

    /** @return Proxy<User> */
    public static function createAdmin(array $args = []): Proxy
    {
        return self::createOne(
            array_merge($args, ['roles' => [UserRoles::Admin]])
        );
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this->afterInstantiate(function (User $user): void {
            $user->setPassword($this->userPasswordHasher->hashPassword($user, $user->getPassword()));
        });
    }

    protected static function getClass(): string
    {
        return User::class;
    }
}
