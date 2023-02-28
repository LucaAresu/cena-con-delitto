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
    public function itShouldRedirectToUserareaIfUserHasLogged(): void
    {
        $user = $this->createUser(false);

        $this->client->request('POST', 'login', [
            'username' => $user->getUsername(),
            'password' => 'test',
        ]);

        $this->client->followRedirect();

        self::assertResponseRedirects('/cena');
    }

    /** @test */
    public function itShouldErrorOnWrongPassword(): void
    {
        $user = $this->createUser(false);

        $this->client->request('POST', 'login', [
            'username' => $user->getUsername(),
            'password' => 'wrong-password',
        ]);

        $this->client->followRedirect();

        $response = $this->client->getResponse()->getContent();

        self::assertStringContainsString('Invalid credentials', $response);
    }

    /** @test */
    public function itShouldRedirectOnAdminareaWhenAdminHasLogged(): void
    {
        $user = $this->createUser(false, [UserRoles::Admin]);

        $this->client->request('POST', 'login', [
            'username' => $user->getUsername(),
            'password' => 'test',
        ]);

        $this->client->followRedirect();

        self::assertResponseRedirects('/admin/');
    }

    /** @test */
    public function itShouldRedirectIfLogged(): void
    {
        $user = $this->createUser(false);
        $this->client->loginUser($user);

        $this->client->request('GET', 'login', [
            'username' => $user->getUsername(),
            'password' => 'test',
        ]);

        self::assertResponseRedirects('/');
    }

    private function createUser(bool $isGuest, array $roles = [UserRoles::User]): User
    {
        $user = UserFactory::createOne([
            'username' => 'billy',
            'password' => 'test',
            'roles' => $roles,
            'is_guest' => $isGuest,
        ]);

        return $user->object();
    }
}
