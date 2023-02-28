<?php

declare(strict_types=1);

namespace App\Tests\CenaConDelitto\UserArea\Controller\LoginController;

use App\Factory\UserFactory;
use CenaConDelitto\Shared\Entity\User;
use CenaConDelitto\Shared\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

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
    public function itShouldCreateAGuestWhenNoUserFound(): void
    {
        $username = 'billy';

        $this->client->request('POST', 'login', [
            'username' => $username,
        ]);

        $this->client->followRedirect();

        self::assertResponseRedirects('/cena');
        $user = $this->userRepository->getByUsername($username);

        self::assertInstanceOf(User::class, $user);
        self::assertTrue($user->isGuest());
    }

    /** @test */
    public function itShouldReturnAUserWhenAlreadyExist(): void
    {
        $username = 'billy';
        $this->createUser($username, true);

        $this->client->request('POST', 'login', [
            'username' => $username,
        ]);

        $this->client->followRedirect();

        self::assertResponseRedirects('/cena');
    }

    /** @test */
    public function itShouldErrorIfUsernameEmpty(): void
    {
        $username = '';

        $this->client->request('POST', 'login', [
            'username' => $username,
        ]);

        $this->client->followRedirect();

        $response = $this->client->getResponse()->getContent();

        self::assertStringContainsString('Invalid credentials', $response);
    }

    /** @test */
    public function itShouldErrorIfUserExistAndIsNotGuest(): void
    {
        $username = 'billy';
        $this->createUser($username, false);

        $this->client->request('POST', 'login', [
            'username' => $username,
        ]);

        $this->client->followRedirect();

        $response = $this->client->getResponse()->getContent();

        self::assertStringContainsString('non è un guest', $response);
    }

    private function createUser(string $username, bool $isGuest): User
    {
        $user = UserFactory::createOne([
            'username' => $username,
            'is_guest' => $isGuest,
        ]);

        return $user->object();
    }
}
