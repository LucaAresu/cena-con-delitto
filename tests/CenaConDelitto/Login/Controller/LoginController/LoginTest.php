<?php

declare(strict_types=1);

namespace App\Tests\CenaConDelitto\Login\Controller\LoginController;

use App\Factory\UserFactory;
use CenaConDelitto\Shared\Entity\User;
use CenaConDelitto\Shared\Enum\UserRoles;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginTest extends WebTestCase
{
    private KernelBrowser $client;

    public function setUp(): void
    {
        $this->client = self::createClient();
        parent::setUp();
    }

    /** @test */
    public function it_should_redirect_to_userarea_if_user_has_logged(): void
    {
        $user = $this->createUser(false);

        $this->client->request('POST', 'login', [
            'username' => $user->getUsername(),
            'password' => 'test'
        ]);

        $this->client->followRedirect();

        self::assertResponseRedirects('/cena');
    }

    /** @test */
    public function it_should_error_on_wrong_password(): void
    {
        $user = $this->createUser(false);

        $this->client->request('POST', 'login', [
            'username' => $user->getUsername(),
            'password' => 'wrong-password'
        ]);

        $this->client->followRedirect();

        $response = $this->client->getResponse()->getContent();

        self::assertStringContainsString('Invalid credentials', $response);
    }

    /** @test */
    public function it_should_redirect_on_adminarea_when_admin_has_logged(): void
    {
        $user = $this->createUser(false, [UserRoles::Admin]);

        $this->client->request('POST', 'login', [
            'username' => $user->getUsername(),
            'password' => 'test'
        ]);

        $this->client->followRedirect();

        self::assertResponseRedirects('/admin/');
    }

    /** @test */
    public function it_should_redirect_if_logged(): void
    {
        $user = $this->createUser(false);
        $this->client->loginUser($user);

        $this->client->request('GET', 'login', [
            'username' => $user->getUsername(),
            'password' => 'test'
        ]);

        self::assertResponseRedirects('/');
    }

    private function createUser(bool $isGuest, array $roles = [UserRoles::User]): User
    {
        $user = UserFactory::createOne([
            'username' => 'billy',
            'password'=> 'test',
            'roles' => $roles,
            'is_guest' => $isGuest
        ]);

        return $user->object();
    }

}
