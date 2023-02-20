<?php

declare(strict_types=1);

namespace CenaConDelitto\Login\UseCase;

use CenaConDelitto\Login\Service\CreateUser;
use CenaConDelitto\Shared\Entity\User;
use CenaConDelitto\Shared\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Webmozart\Assert\Assert;

readonly class GuestAccessUseCase
{

    private const USERNAME = 'username';

    public function __construct(private CreateUser $createUser, private UserRepository $userRepository) {}

    public function execute(Request $request): User
    {
        Assert::string($username = $request->get(self::USERNAME), 'Non è stato inserito nessun username');

        $user = $this->userRepository->getByUsername($username);

        if ($user) {
            if (true === $user->isGuest()) {
                return $user;
            }

            throw new AccessDeniedException('Questo utente non è di tipo guest');
        }

        return $this->createUser->createGuest($username);
    }
}
