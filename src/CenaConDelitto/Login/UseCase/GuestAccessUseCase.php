<?php

declare(strict_types=1);

namespace CenaConDelitto\Login\UseCase;

use CenaConDelitto\Login\Dto\GuestAccessRequest;
use CenaConDelitto\Login\Service\CreateUser;
use CenaConDelitto\Shared\Entity\User;
use CenaConDelitto\Shared\Repository\UserRepository;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

readonly class GuestAccessUseCase
{
    public function __construct(private CreateUser $createUser, private UserRepository $userRepository)
    {
    }

    public function execute(GuestAccessRequest $request): User
    {
        $user = $this->userRepository->getByUsername($request->username());

        if (!$user) {
            return $this->createUser->createGuest($request->username());
        }
        if (true === $user->isGuest()) {
            return $user;
        }

        throw new AccessDeniedException('Questo utente non è di tipo guest');
    }
}
