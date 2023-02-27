<?php

declare(strict_types=1);

namespace CenaConDelitto\Login\EventListener\Security\Http\Event\CheckPassportEvent;

use CenaConDelitto\Login\Exception\NotAGuestException;
use CenaConDelitto\Login\Service\IsRequestGoodForUser;
use CenaConDelitto\Shared\Entity\User;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Event\CheckPassportEvent;

readonly class AllowGuestToLogin
{
    public function __construct(private IsRequestGoodForUser $isRequestGoodForUser)
    {
    }

    public function checkGuestUser(CheckPassportEvent $event): void
    {
        if (false === $this->isRequestGoodForUser->execute()) {
            return;
        }

        $passport = $event->getPassport();
        if (false === $passport->hasBadge(PasswordCredentials::class)) {
            return;
        }

        $user = $event->getPassport()->getUser();

        if (false === $user instanceof User) {
            return;
        }

        if (false === $user->isGuest()) {
            throw new NotAGuestException();
        }

        /** @var PasswordCredentials $badge */
        $badge = $passport->getBadge(PasswordCredentials::class);
        $badge->markResolved();
    }
}
