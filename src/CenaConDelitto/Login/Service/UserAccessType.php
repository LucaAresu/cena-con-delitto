<?php

declare(strict_types=1);

namespace CenaConDelitto\Login\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class UserAccessType
{
    private null|Request $request = null;

    public function __construct(private readonly RequestStack $requestStack)
    {
    }

    public function isGuestAccess(): bool
    {
        if (false === $this->isAccess()) {
            return false;
        }

        if ($this->getRequest()?->get('password')) {
            return false;
        }

        return true;
    }

    public function isUserAccess(): bool
    {
        if (false === $this->isAccess()) {
            return false;
        }

        if ($this->getRequest()?->get('password')) {
            return true;
        }

        return false;
    }

    private function isAccess(): bool
    {
        $request = $this->getRequest();

        if (null === $request) {
            return false;
        }

        if ('POST' !== $request->getMethod() || '/login' !== $request->getRequestUri()) {
            return false;
        }

        $username = $request->get('username');
        /** @noinspection IfReturnReturnSimplificationInspection */
        if (null === $username || '' === $username) {
            return false;
        }

        return true;
    }

    private function getRequest(): null|Request
    {
        if (null === $this->request) {
            $this->request = $this->requestStack->getCurrentRequest();
        }

        return $this->request;
    }
}
