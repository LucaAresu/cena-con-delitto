<?php

declare(strict_types=1);

namespace CenaConDelitto\Login\Dto;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class GuestAccessRequest implements \CenaConDelitto\Shared\Dto\RequestDto
{
    #[Assert\NotBlank]
    private string $username;

    private function __construct(string $username)
    {
        $this->username = $username;
    }

    public static function createFromRequest(Request $request): self
    {
        /* @phpstan-ignore-next-line */
        return new self((string) $request->get('username'));
    }

    public function username(): string
    {
        return $this->username;
    }
}
