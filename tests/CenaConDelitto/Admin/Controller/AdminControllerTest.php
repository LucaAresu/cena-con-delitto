<?php

declare(strict_types=1);

namespace App\Tests\CenaConDelitto\Admin\Controller;

use App\Factory\UserFactory;
use CenaConDelitto\Shared\Enum\UserRoles;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminControllerTest extends WebTestCase
{
    private KernelBrowser $client;

    public function setUp(): void
    {
        $this->client = self::createClient();
        parent::setUp();
    }

    /** @test */
    public function it_should_work_when_admin_access(): void
    {
        $user = UserFactory::createOne([
            'roles' => [UserRoles::Admin]
        ]);

        $this->client->loginUser($user->object());

        $this->client->request('GET', 'admin');
        $this->client->followRedirect();

        self::assertResponseIsSuccessful();
    }

    /** @test */
    public function it_should_access_denied_when_user_tries_to_access(): void
    {
        $user = UserFactory::createOne();
        $this->client->loginUser($user->object());

        $this->client->request('GET', 'admin');

        self::assertResponseStatusCodeSame(403);
    }

    /** @test */
    public function it_should_redirect_to_login_if_not_logged(): void
    {
        $this->client->request('GET', 'admin');

        self::assertResponseRedirects('http://localhost/login');
    }
}
