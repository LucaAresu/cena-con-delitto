<?php

declare(strict_types=1);

namespace App\Tests\CenaConDelitto\UserArea\Controller;

use App\Factory\UserFactory;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    private KernelBrowser $client;

    public function setUp(): void
    {
        $this->client = self::createClient();
        parent::setUp();
    }

    /** @test */
    public function itShouldSuccessWhenLoggedUserAccess(): void
    {
        $user = UserFactory::createOne();
        $this->client->loginUser($user->object());

        $this->client->request('GET', 'cena');

        self::assertResponseIsSuccessful();
    }

    /** @test */
    public function itShouldRedirectToLoginIfNotLogged(): void
    {
        $this->client->request('GET', 'cena');

        self::assertResponseRedirects('http://localhost/login');
    }
}
