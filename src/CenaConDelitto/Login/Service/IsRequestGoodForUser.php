<?php

declare(strict_types=1);

namespace CenaConDelitto\Login\Service;

use http\Exception\RuntimeException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

readonly class IsRequestGoodForUser
{
    private null|Request $request;

    public function __construct(RequestStack $requestStack)
    {
        $this->request = $requestStack->getCurrentRequest();
    }

    public function execute(): bool
    {
        if (null === $this->request) {
            throw new RuntimeException('Request is null');
        }

        if ('POST' !== $this->request->getMethod() || '/login' !== $this->request->getRequestUri()) {
            return false;
        }

        if ($this->request->get('password')) {
            return false;
        }

        $username = $this->request->get('username');
        if (null === $username || '' === $username) {
            return false;
        }

        return true;
    }
}
