<?php

namespace CenaConDelitto\Shared\Entity;

use CenaConDelitto\Shared\Repository\DinnerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: DinnerRepository::class)]
class Dinner
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 127)]
    private string $name;

    #[ORM\Column(type: 'uuid', unique: true)]
    private Uuid $uuid;

    /** @var Collection<int, Character> $characters */
    #[ORM\ManyToMany(targetEntity: Character::class, mappedBy: 'cena')]
    private Collection $characters;

    public function __construct(Uuid $uuid, string $name)
    {
        $this->uuid = $uuid;
        $this->name = $name;
        $this->characters = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getUuid(): ?Uuid
    {
        return $this->uuid;
    }

    public function setUuid(Uuid $uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }

    /**
     * @return Collection<int, Character>
     */
    public function getCharacters(): Collection
    {
        return $this->characters;
    }

    public function addCharacter(Character $character): self
    {
        if (!$this->characters->contains($character)) {
            $this->characters->add($character);
            $character->addDinner($this);
        }

        return $this;
    }

    public function removeCharacter(Character $character): self
    {
        if ($this->characters->removeElement($character)) {
            $character->removeDinner($this);
        }

        return $this;
    }
}
