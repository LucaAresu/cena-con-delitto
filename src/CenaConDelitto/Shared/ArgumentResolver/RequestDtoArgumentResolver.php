<?php

declare(strict_types=1);

namespace CenaConDelitto\Shared\ArgumentResolver;

use CenaConDelitto\Shared\Dto\RequestDto;
use CenaConDelitto\Shared\Exception\RequestValidationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RequestDtoArgumentResolver implements ValueResolverInterface
{
    public function __construct(private ValidatorInterface $validator)
    {
    }

    /**
     * @return iterable<RequestDto>
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $argumentType = $argument->getType();
        if (
            !$argumentType
            || !is_subclass_of($argumentType, RequestDto::class, true)
        ) {
            return [];
        }

        $dto = $argumentType::createFromRequest($request);

        $errors = $this->validator->validate($dto);

        if (count($errors)) {
            throw RequestValidationException::create($errors);
        }

        return [$dto];
    }
}
