<?php

namespace CenaConDelitto\Shared\Entity;

use CenaConDelitto\Shared\Repository\DinnerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Attributes as OA;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: DinnerRepository::class)]
#[OA\Schema(
    title: 'Dinner',
    description: 'Dinner Entity',
    properties: [
        new OA\Property(property: 'uuid', title: 'uuid', type: 'string', example: '0186bc77-eeee-7fc2-ab19-bcc2602e7857'),
        new OA\Property(property: 'name', title: 'the name', type: 'string', example: 'dinner name'),
        new OA\Property(property: 'characters', title: 'the characters', type: 'array', items: new OA\Items(
                title: 'uuid', type: 'string', example: '0186bc77-eeee-7fc2-ab19-bcc2602e7857', description: 'can be empty'
            )
        ),
    ]
)]
class Dinner
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Ignore]
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
