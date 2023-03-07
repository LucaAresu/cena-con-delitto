<?php

namespace CenaConDelitto\Shared\Entity;

use CenaConDelitto\Shared\Repository\CharacterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: CharacterRepository::class)]
class Character
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'uuid', unique: true)]
    private Uuid $uuid;

    #[ORM\Column(length: 255, unique: true)]
    private string $name;

    /** @var Collection<int, Dinner> $dinners */
    #[ORM\ManyToMany(targetEntity: Dinner::class, inversedBy: 'characters')]
    private Collection $dinners;

    #[ORM\Column(length: 512)]
    private string $shortDescription;

    #[ORM\Column(type: Types::TEXT)]
    private string $description;

    public function __construct(Uuid $uuid, string $name, string $shortDescription, string $description)
    {
        $this->uuid = $uuid;
        $this->name = $name;
        $this->shortDescription = $shortDescription;
        $this->description = $description;
        $this->dinners = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Dinner>
     */
    public function getDinner(): Collection
    {
        return $this->dinners;
    }

    public function addDinner(Dinner $cena): self
    {
        if (!$this->dinners->contains($cena)) {
            $this->dinners->add($cena);
        }

        return $this;
    }

    public function removeDinner(Dinner $cena): self
    {
        $this->dinners->removeElement($cena);

        return $this;
    }

    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    public function setShortDescription(string $shortDescription): self
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
