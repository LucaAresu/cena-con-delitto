<?php

declare(strict_types=1);

namespace CenaConDelitto\Login\EventListener\Kernel\Request;

use CenaConDelitto\Login\Service\CreateUser;
use CenaConDelitto\Login\Service\UserAccessType;
use CenaConDelitto\Shared\Repository\UserRepository;
use Symfony\Component\HttpKernel\Event\RequestEvent;

readonly class CheckAndCreateUser
{
    public function __construct(private UserAccessType $userAccessType, private CreateUser $createUser, private UserRepository $userRepository)
    {
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $isGuestAccess = $this->userAccessType->isGuestAccess();
        $isUserAccess = $this->userAccessType->isUserAccess();

        if (false === $isGuestAccess && false === $isUserAccess) {
            return;
        }

        $request = $event->getRequest();
        /** @var string $username */
        $username = $request->get('username');

        $user = $this->userRepository->getByUsername($username);

        if ($user) {
            return;
        }

        if ($isGuestAccess) {
            $this->createUser->createGuest($username);

            return;
        }
        /** @var string $password */
        $password = $request->get('password');

        $this->createUser->createUser($username, $password);
    }
}
