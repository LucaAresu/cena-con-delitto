<?php

declare(strict_types=1);

namespace App\Tests\CenaConDelitto\Shared\Event\Doctrine;

use CenaConDelitto\Shared\Entity\User;
use CenaConDelitto\Shared\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserTest extends KernelTestCase
{

    /** @test */
    public function it_should_change_user_password(): void
    {
        $container = self::getContainer();
        $userRepository = $container->get(UserRepository::class);
        $userPasswordHasher = $container->get('security.user_password_hasher');

        $password = 'fsafsa';

        $user = new User();
        $user->setUuid('59575c2c-ad4d-11ed-afa1-0242ac120002')
            ->setUsername('fsafsa')
            ->setPlainPassword($password);

        $userRepository->save($user, flush: true);

        self::assertNull($user->getPlainPassword());
        self::assertTrue(
            $userPasswordHasher->isPasswordValid($user, $password)
        );
    }
}
