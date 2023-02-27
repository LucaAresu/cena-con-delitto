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
    public function it_should_success_when_logged_user_access(): void
    {
        $user = UserFactory::createOne();
        $this->client->loginUser($user->object());

        $this->client->request('GET', 'cena');

        self::assertResponseIsSuccessful();
    }

    /** @test */
    public function it_should_redirect_to_login_if_not_logged(): void
    {
        $this->client->request('GET', 'cena');

        self::assertResponseRedirects('http://localhost/login');
    }
}
