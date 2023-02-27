<?php

declare(strict_types=1);

namespace App\Tests\CenaConDelitto\UserArea\Controller\LoginController;

use App\Factory\UserFactory;
use CenaConDelitto\Shared\Entity\User;
use CenaConDelitto\Shared\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

class GuestTest extends WebTestCase
{
    private KernelBrowser $client;
    private UserRepository $userRepository;

    public function setUp(): void
    {
        $this->client = self::createClient();
        $container = self::getContainer();
        $this->userRepository = $container->get(UserRepository::class);
        parent::setUp();
    }

    /** @test */
    public function it_should_create_a_guest_when_no_user_found(): void
    {
        $username = 'billy';

        $this->client->request('POST', 'login', [
            'username' => $username
        ]);

        $this->client->followRedirect();

        self::assertResponseRedirects('/cena');
    }

    /** @test */
    public function it_should_return_a_user_when_already_exist(): void
    {
        $username = 'billy';
        $this->createUser($username, true);

        $this->client->request('POST', 'login', [
            'username' => $username
        ]);

        $this->client->followRedirect();

        self::assertResponseRedirects('/cena');
    }

    /** @test */
    public function it_should_error_if_username_empty(): void
    {
        $username = '';

        $this->client->request('POST', 'login', [
            'username' => $username
        ]);

        $this->client->followRedirect();

        $response = $this->client->getResponse()->getContent();

        self::assertStringContainsString('Invalid credentials', $response);
    }

    /** @test */
    public function it_should_error_if_user_exist_and_is_not_guest(): void
    {
        $username = 'billy';
        $this->createUser($username, false);

        $this->client->request('POST', 'login', [
            'username' => $username
        ]);

        $this->client->followRedirect();

        $response = $this->client->getResponse()->getContent();

        self::assertStringContainsString('non è un guest', $response);
    }

    private function createUser(string $username, bool $isGuest): User
    {
        $user = UserFactory::createOne([
            'username' => $username,
            'is_guest' => $isGuest
        ]);

        return $user->object();
    }

}
