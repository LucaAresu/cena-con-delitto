<?php

declare(strict_types=1);

namespace CenaConDelitto\Admin\Dto;

use CenaConDelitto\Shared\Dto\RequestDto;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

#[OA\Schema(
    title: 'Create Dinner Request',
    properties: [
        new OA\Property(property: 'uuid', title: 'uuid', type: 'string', example: '0186bc77-eeee-7fc2-ab19-bcc2602e7857'),
        new OA\Property(property: 'name', title: 'the name', type: 'string', example: 'dinner name'),
    ],
    required: ['name']
)]
readonly class CreateDinnerRequest implements RequestDto
{
    #[Assert\Uuid]
    private null|string $uuid;

    #[Assert\NotBlank]
    private string $name;

    private function __construct(string $name, null|string $uuid)
    {
        $this->name = $name;
        $this->uuid = $uuid;
    }

    public static function createFromRequest(Request $request): RequestDto
    {
        return new self(
            (string) $request->request->get('name'),
            (string) $request->request->get('uuid')
        );
    }

    public function uuid(): null|string
    {
        return $this->uuid;
    }

    public function name(): string
    {
        return $this->name;
    }
}
