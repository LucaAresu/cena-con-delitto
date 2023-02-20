<?php

namespace CenaConDelitto\Shared\Entity;

use CenaConDelitto\Shared\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true, type: UuidType::NAME)]
    private Uuid $uuid;

    /** @var array<string> */
    #[ORM\Column]
    private array $roles = [];

    /** The hashed password */
    #[ORM\Column]
    private string $password = '';

    #[ORM\Column(length: 255, unique: true)]
    private string $username;
    private ?string $plainPassword = null;

    #[ORM\Column]
    private bool $is_guest;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid(): Uuid
    {
        return $this->uuid;
    }

    public function setUuid(Uuid $uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return $this->uuid;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /** @param array<string> $roles */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        $this->plainPassword = null;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(string|null $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    public function updatePassword(string $password): self
    {
        $this->setPassword('');
        $this->setPlainPassword($password);

        return $this;
    }

    public function isGuest(): bool
    {
        return $this->is_guest;
    }

    public function setIsGuest(bool $is_guest): self
    {
        $this->is_guest = $is_guest;

        return $this;
    }
}
