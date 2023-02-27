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
        $this->client->followRedirects(true);
        $container = self::getContainer();
        $this->userRepository = $container->get(UserRepository::class);
        parent::setUp();
    }

    /** @test */
    public function it_should_create_a_guest_when_no_user_found(): void
    {
        $username = 'billy';

        $this->client->request('POST', 'guest-access', [
            'username' => $username
        ]);

        self::assertResponseIsSuccessful();

        $response = json_decode((string)$this->client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);

        self::assertIsArray($response);
        self::assertArrayHasKey('uuid', $response);

        $user = $this->userRepository->get($response['uuid']);

        self::assertSame($username, $user->getUsername());
    }

    /** @test */
    public function it_should_return_a_user_when_already_exist(): void
    {
        $username = 'billy';
        $user = $this->createUser($username, true);

        $this->client->request('POST', 'guest-access', [
            'username' => $username
        ]);

        self::assertResponseIsSuccessful();

        $response = json_decode((string)$this->client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);

        self::assertIsArray($response);
        self::assertArrayHasKey('uuid', $response);
        self::assertSame($user->getUuid()->toRfc4122(), $response['uuid']);
    }

    /** @test */
    public function it_should_error_if_user_exist_and_is_not_guest(): void
    {
        $username = 'billy';
        $this->createUser($username, false);

        $this->client->request('POST', 'guest-access', [
            'username' => $username
        ]);

        self::assertResponseStatusCodeSame(500);
    }

    /** @test */
    public function it_should_error_if_already_logged(): void
    {
        $user = $this->createUser('billy', true);
        $this->client->loginUser($user);

        $this->client->request('POST', 'guest-access', [
            'username' => 'some-username'
        ]);

        $response = json_decode($this->client->getResponse()->getContent(), true);

        self::assertResponseStatusCodeSame(500);
        self::assertIsArray($response);
        self::assertArrayHasKey('error', $response);
        self::assertStringContainsString('already logged', $response['error']);
    }

    /** @test */
    public function it_should_error_if_empty_or_missing_username(): void
    {
        $this->client->request('POST', 'guest-access');

        $response = json_decode($this->client->getResponse()->getContent(), true);

        self::assertResponseStatusCodeSame(400);
        self::assertIsArray($response);
        self::assertArrayHasKey('errors', $response);
        self::assertStringContainsString('should not be blank', $response['errors']['username']);
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