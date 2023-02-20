<?php

declare(strict_types=1);

namespace App\Tests\CenaConDelitto\UserArea\Controller\LoginController;

use App\Factory\UserFactory;
use CenaConDelitto\Shared\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\AbstractBrowser;

class GuestTest extends WebTestCase
{
    private AbstractBrowser $client;
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

        $response = json_decode($this->client->getResponse()->getContent(), true);

        $user = $this->userRepository->get($response['uuid']);

        self::assertSame($username, $user->getUsername());
    }

    /** @test */
    public function it_should_return_a_user_when_already_exist(): void
    {
        $username = 'billy';
        $user = UserFactory::createOne([
            'username' => $username,
            'is_guest' => true,
        ]);

        $this->client->request('POST', 'guest-access', [
            'username' => $username
        ]);

        self::assertResponseIsSuccessful();

        $response = json_decode($this->client->getResponse()->getContent(), true);

        self::assertSame($user->getUuid()->toRfc4122(), $response['uuid']);
    }

    /** @test */
    public function it_should_error_if_user_exist_and_is_not_guest(): void
    {
        $username = 'billy';
        $user = UserFactory::createOne([
            'username' => $username,
            'is_guest' => false,
        ]);

        $this->client->request('POST', 'guest-access', [
            'username' => $username
        ]);

        self::assertResponseStatusCodeSame(500);
    }

}
