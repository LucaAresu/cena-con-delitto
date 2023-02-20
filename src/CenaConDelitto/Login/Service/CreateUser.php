<?php

declare(strict_types=1);

namespace CenaConDelitto\Login\Service;

use CenaConDelitto\Shared\Entity\User;
use CenaConDelitto\Shared\Repository\UserRepository;
use Symfony\Component\Uid\Factory\UuidFactory;

readonly class CreateUser
{
    public function __construct(private UuidFactory $uuidFactory, private UserRepository $userRepository)
    {
    }

    public function createGuest(string $username): User
    {
        $user = $this->createUser($username)
            ->setIsGuest(true);

        $this->userRepository->save($user);

        return $user;
    }

    public function createUser(string $username): User
    {
        $user = $this->create($username)
            ->setIsGuest(false);

        $this->userRepository->save($user);

        return $user;
    }

    private function create(string $username): User
    {
        $user = new User();
        $user->setUuid($this->uuidFactory->create())
            ->setUsername($username);

        return $user;
    }
}
