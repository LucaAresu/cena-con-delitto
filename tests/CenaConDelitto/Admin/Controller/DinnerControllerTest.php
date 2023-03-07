<?php

declare(strict_types=1);

namespace App\Tests\CenaConDelitto\Admin\Controller;

use App\Factory\UserFactory;
use CenaConDelitto\Shared\Entity\Dinner;
use CenaConDelitto\Shared\Repository\DinnerRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Uid\Uuid;

class DinnerControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private DinnerRepository $dinnerRepository;

    public function setUp(): void
    {
        $this->client = self::createClient();
        $user = UserFactory::createAdmin();
        $this->client->loginUser($user->object());

        $container = self::getContainer();
        /* @phpstan-ignore-next-line */
        $this->dinnerRepository = $container->get(DinnerRepository::class);

        parent::setUp();
    }

    /** @test */
    public function itShouldCreateADinner(): void
    {
        $name = 'random dinner';
        $this->client->request('POST', 'admin/cena', [
            'name' => $name,
        ]);

        self::assertResponseIsSuccessful();
        $dinner = $this->dinnerRepository->getByName($name);

        self::assertInstanceOf(Dinner::class, $dinner);
    }

    /** @test */
    public function itShouldCreateADinnerWithUuid(): void
    {
        $uuid = Uuid::v7();
        $name = 'random dinner';
        $this->client->request('POST', 'admin/cena', [
            'uuid' => $uuid->toRfc4122(),
            'name' => $name,
        ]);

        self::assertResponseIsSuccessful();
        $dinner = $this->dinnerRepository->get($uuid);

        self::assertInstanceOf(Dinner::class, $dinner);
    }

    /** @test */
    public function itShouldErrorIfNoNameGiven(): void
    {
        $this->client->request('POST', 'admin/cena', [
            'name' => ''
        ]);

        self::assertResponseStatusCodeSame(400);
    }
}
