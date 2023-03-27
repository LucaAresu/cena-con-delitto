<?php

declare(strict_types=1);

namespace App\Tests\CenaConDelitto\Admin\Controller;

use App\Factory\DinnerFactory;
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

    /** @test */
    public function itShouldDeleteADinner(): void
    {
        $dinner = new Dinner(Uuid::fromString('db22b2df-6838-4575-822d-5929325675ea'), 'random name', false);
        $this->dinnerRepository->save($dinner);

        $this->client->request('DELETE', sprintf('admin/cena/%s', $dinner->getUuid()));

        self::assertResponseIsSuccessful();
        self::assertNull($this->dinnerRepository->get($dinner->getUuid()));
        self::assertEmpty($this->client->getRequest()->getContent());
    }

    /** @test */
    public function itShould404WhenNoDinner(): void
    {
        $this->client->request('DELETE', 'admin/cena/db22b2df-6838-4575-822d-5929325675ea');

        self::assertResponseStatusCodeSame(404);
        self::assertStringContainsString('not found', $this->client->getResponse()->getContent());
    }
}
