<?php

declare(strict_types=1);

namespace CenaConDelitto\Login\EventListener\Kernel\Request;

use CenaConDelitto\Login\Service\CreateUser;
use CenaConDelitto\Login\Service\IsRequestGoodForUser;
use CenaConDelitto\Shared\Repository\UserRepository;
use Symfony\Component\HttpKernel\Event\RequestEvent;

readonly class CreateGuest
{
    public function __construct(private IsRequestGoodForUser $isRequestGoodForUser, private CreateUser $createUser, private UserRepository $userRepository)
    {
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (false === $this->isRequestGoodForUser->execute()) {
            return;
        }

        $request = $event->getRequest();
        /** @var string $username */
        $username = $request->get('username');

        $user = $this->userRepository->getByUsername($username);

        if ($user) {
            return;
        }

        $this->createUser->createGuest($username);
    }
}
