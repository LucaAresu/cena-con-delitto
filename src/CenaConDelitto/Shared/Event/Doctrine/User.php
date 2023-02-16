<?php

declare(strict_types=1);

namespace CenaConDelitto\Shared\Event\Doctrine;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

readonly class User
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function prePersist(\CenaConDelitto\Shared\Entity\User $user): void
    {
        $this->updatePassword($user);
    }

    public function preUpdate(\CenaConDelitto\Shared\Entity\User $user): void
    {
        $this->updatePassword($user);
    }

    private function updatePassword(\CenaConDelitto\Shared\Entity\User $user): void
    {
        if (!$user->getPlainPassword()) {
            return;
        }

        $user->setPassword(
            $this->passwordHasher->hashPassword($user, $user->getPlainPassword())
        );

        $user->setPlainPassword(null);
    }
}
