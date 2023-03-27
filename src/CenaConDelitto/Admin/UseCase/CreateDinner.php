<?php

declare(strict_types=1);

namespace CenaConDelitto\Admin\UseCase;

use CenaConDelitto\Admin\Dto\CreateDinnerRequest;
use CenaConDelitto\Shared\Entity\Dinner;
use CenaConDelitto\Shared\Repository\DinnerRepository;
use Symfony\Component\Uid\Factory\UuidFactory;
use Symfony\Component\Uid\Uuid;
use Webmozart\Assert\Assert;

readonly class CreateDinner
{
    public function __construct(private DinnerRepository $dinnerRepository, private UuidFactory $uuidFactory)
    {
    }

    public function execute(CreateDinnerRequest $request): Dinner
    {
        $uuid = $request->uuid() ? Uuid::fromString($request->uuid()) : $this->uuidFactory->create();
        $dinner = new Dinner(
            uuid: $uuid,
            name: $request->name(),
            active: false,
        );

        $this->dinnerRepository->save($dinner);

        $dinner = $this->dinnerRepository->get($uuid);
        Assert::isInstanceOf($dinner, Dinner::class);

        return $dinner;
    }
}
