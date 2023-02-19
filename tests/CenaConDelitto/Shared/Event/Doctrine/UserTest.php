<?php

declare(strict_types=1);

namespace App\Tests\CenaConDelitto\Shared\Event\Doctrine;

use CenaConDelitto\Shared\Entity\User;
use CenaConDelitto\Shared\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserTest extends KernelTestCase
{

    private UserRepository $userRepository;
    private UserPasswordHasherInterface $userPasswordHasher;
    public function setUp(): void
    {
        $container = self::getContainer();
        $this->userRepository = $container->get(UserRepository::class);
        $this->userPasswordHasher = $container->get('security.user_password_hasher');
    }

    /** @test */
    public function it_should_change_user_password_on_creation(): void
    {
        $password = 'test';
        $user = $this->createUser($password);

        self::assertNull($user->getPlainPassword());
        self::assertTrue($this->userPasswordHasher->isPasswordValid($user, $password));
    }

    /** @test */
    public function it_should_change_user_password_on_update(): void
    {
        $user = $this->createUser('changeMe');

        $password = 'test';
        $p = [];
        $p [] = $user->getPassword();
        $user->updatePassword($password);
        $this->userRepository->save($user);
        $p [] = $user->getPassword();

        $user = $this->userRepository->get($user->getUuid());
        $p [] = $user->getPassword();

        self::assertTrue($this->userPasswordHasher->isPasswordValid($user, $password));
    }

    private function createUser(string $password): User
    {
        $user = new User();
        $user->setUuid('59575c2c-ad4d-11ed-afa1-0242ac120002')
            ->setUsername('fsafsa')
            ->updatePassword($password);

        $this->userRepository->save($user);

        return $user;
    }
}
