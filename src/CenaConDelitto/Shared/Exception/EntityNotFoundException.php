<?php

declare(strict_types=1);

namespace CenaConDelitto\Shared\Exception;

class EntityNotFoundException extends \RuntimeException
{
    public static function crea(string $class, string $identifier): self
    {
        return new self(sprintf('Entity with class %s and identifier %s not found', $class, $identifier));
    }
}
