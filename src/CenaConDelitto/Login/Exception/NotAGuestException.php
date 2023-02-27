<?php

declare(strict_types=1);

namespace CenaConDelitto\Login\Exception;

use Symfony\Component\Security\Core\Exception\AuthenticationException;

class NotAGuestException extends AuthenticationException
{
    public function getMessageKey(): string
    {
        return 'L\'utente non è un guest.';
    }
}
