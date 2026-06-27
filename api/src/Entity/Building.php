<?php

namespace App\Entity;

use App\Repository\BuildingRepository;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Attribute\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BuildingRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_BUILDING_IDENTIFIER', columns: ['gls_identifier'])]
#[ORM\UniqueConstraint(name: 'UNIQ_BUILDING_SLUG', columns: ['gls_slug'])]
#[ORM\Table(name: 'building')]
#[ORM\HasLifecycleCallbacks]
class Building
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['building', 'character'])]
    private ?int $id = null;

    #[ORM\Column(length: 40, name: 'gls_identifier')]
    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Assert\Length(min: 40, max: 40)]
    #[Groups(['building', 'character'])]
    private ?string $identifier = null;

    #[ORM\Column(length: 40, name: 'gls_name')]
    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 20)]
    #[Groups(['building'])]
    private ?string $name = null;

    #[ORM\Column(length: 40, name: 'gls_slug')]
    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 20)]
    #[Groups(['building'])]
    private ?string $slug = null;

    #[ORM\Column(length: 40, name: 'gls_caste')]
    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 20)]
    #[Groups(['building'])]
    private ?string $caste = null;

    #[ORM\Column(type: Types::SMALLINT, name: 'gls_strength')]
    #[Assert\PositiveOrZero]
    #[Groups(['building'])]
    private ?int $strength = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true, name: 'gls_price')]
    #[Assert\PositiveOrZero]
    #[Groups(['building'])]
    private ?int $price = null;

    #[ORM\Column(length: 120, nullable: true, name: 'gls_image')]
    #[Assert\Length(min: 5, max: 50)]
    #[Groups(['building'])]
    private ?string $image = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, name: 'gls_creation')]
    #[Groups(['building'])]
    private ?DateTimeImmutable $creation = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, name: 'gls_modification')]
    #[Groups(['building'])]
    private ?DateTimeImmutable $modification = null;

    /**
     * @var Collection<int, Character>
     */
    #[ORM\OneToMany(targetEntity: Character::class, mappedBy: 'building')]
    #[Groups(['building'])]
    private Collection $characters;

    private array $links = [];

    public function __construct()
    {
        $this->creation = new DateTimeImmutable();
        $this->characters = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdentifier(): ?string
    {
        return $this->identifier;
    }

    public function setIdentifier(string $identifier): void
    {
        $this->identifier = $identifier;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    public function getCaste(): ?string
    {
        return $this->caste;
    }

    public function setCaste(string $caste): void
    {
        $this->caste = $caste;
    }

    public function getStrength(): ?int
    {
        return $this->strength;
    }

    public function setStrength(int $strength): void
    {
        $this->strength = $strength;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price): void
    {
        $this->price = $price;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): void
    {
        $this->image = $image;
    }

    public function getCreation(): ?DateTimeImmutable
    {
        return $this->creation;
    }

    public function setCreation(DateTimeImmutable $creation): void
    {
        $this->creation = $creation;
    }

    public function getModification(): ?DateTimeImmutable
    {
        return $this->modification;
    }

    public function setModification(DateTimeImmutable $modification): static
    {
        $this->modification = $modification;

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
            $character->setBuilding($this);
        }

        return $this;
    }

    public function removeCharacter(Character $character): self
    {
        if ($this->characters->removeElement($character)) {
            if ($character->getBuilding() === $this) {
                $character->setBuilding(null);
            }
        }

        return $this;
    }

    #[SerializedName('_links')]
    public function getLinks(): array
    {
        return $this->links;
    }

    public function setLinks(array $links): void
    {
        $this->links = $links;
    }

    #[ORM\PrePersist]
    public function touchModificationOnPrePersist(): void
    {
        $this->modification = new DateTimeImmutable();
    }

    #[ORM\PreUpdate]
    public function touchModificationOnPreUpdate(): void
    {
        $this->modification = new DateTimeImmutable();
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $data = get_object_vars($this);
        unset($data['characters'], $data['links']);
        foreach (['creation', 'modification'] as $dateField) {
            if ($data[$dateField] instanceof DateTimeImmutable) {
                $data[$dateField] = $data[$dateField]->format(DateTimeInterface::ATOM);
            }
        }

        return $data;
    }
}
