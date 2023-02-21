<?php

declare(strict_types=1);

namespace CenaConDelitto\Shared\Dto;

use Symfony\Component\HttpFoundation\Request;

interface RequestDto
{
    public static function createFromRequest(Request $request): self;
}
