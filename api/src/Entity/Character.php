<?php

namespace App\Entity;

use App\Repository\CharacterRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Attribute\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CharacterRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_CHARACTER_IDENTIFIER', columns: ['gls_identifier'])]
#[ORM\UniqueConstraint(name: 'UNIQ_CHARACTER_SLUG', columns: ['gls_slug'])]
#[ORM\Table(name: '`character`')]
#[ORM\HasLifecycleCallbacks]
class Character
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['character', 'building'])]
    private ?int $id = null;

    #[ORM\Column(length: 20, name: 'gls_name')]
    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 20)]
    #[Groups(['character'])]
    private ?string $name = null;

    #[ORM\Column(length: 40, name: 'gls_identifier')]
    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Assert\Length(min: 40, max: 40)]
    #[Groups(['character', 'building'])]
    private ?string $identifier = null;

    #[ORM\Column(length: 20, name: 'gls_slug')]
    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 20)]
    #[Groups(['character'])]
    private ?string $slug = null;

    #[ORM\Column(length: 20, name: 'gls_kind')]
    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 20)]
    #[Groups(['character'])]
    private ?string $kind = null;

    #[ORM\Column(length: 50, name: 'gls_surname')]
    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 50)]
    #[Groups(['character'])]
    private ?string $surname = null;

    #[ORM\Column(length: 20, nullable: true, name: 'gls_caste')]
    #[Assert\Length(min: 3, max: 20)]
    #[Groups(['character'])]
    private ?string $caste = null;

    #[ORM\Column(length: 20, nullable: true, name: 'gls_knowledge')]
    #[Assert\Length(min: 3, max: 20)]
    #[Groups(['character'])]
    private ?string $knowledge = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true, name: 'gls_intelligence')]
    #[Assert\PositiveOrZero]
    #[Groups(['character'])]
    private ?int $intelligence = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true, name: 'gls_strength')]
    #[Assert\PositiveOrZero]
    #[Groups(['character'])]
    private ?int $strength = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true, name: 'gls_life')]
    #[Assert\PositiveOrZero]
    #[Groups(['character'])]
    private ?int $life = null;

    #[ORM\Column(length: 50, nullable: true, name: 'gls_image')]
    #[Assert\Length(min: 5, max: 50)]
    #[Groups(['character'])]
    private ?string $image = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, name: 'gls_creation')]
    #[Groups(['character'])]
    private ?DateTimeImmutable $creation = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, name: 'gls_modification')]
    #[Groups(['character'])]
    private ?DateTimeImmutable $modification = null;

    #[ORM\ManyToOne(inversedBy: 'characters')]
    #[ORM\JoinColumn(name: 'gls_building_id', referencedColumnName: 'id', nullable: true, onDelete: 'SET NULL')]
    #[Groups(['character'])]
    private ?Building $building = null;

    #[ORM\ManyToOne(inversedBy: 'characters')]
    #[ORM\JoinColumn(name: 'gls_user_id', referencedColumnName: 'id', nullable: true, onDelete: 'SET NULL')]
    #[Groups(['character'])]
    private ?User $user = null;

    private array $links = [];

    public function __construct()
    {
        $this->creation = new DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getIdentifier(): ?string
    {
        return $this->identifier;
    }

    public function setIdentifier(string $identifier): void
    {
        $this->identifier = $identifier;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    public function getKind(): ?string
    {
        return $this->kind;
    }

    public function setKind(string $kind): void
    {
        $this->kind = $kind;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): void
    {
        $this->surname = $surname;
    }

    public function getCaste(): ?string
    {
        return $this->caste;
    }

    public function setCaste(?string $caste): void
    {
        $this->caste = $caste;
    }

    public function getKnowledge(): ?string
    {
        return $this->knowledge;
    }

    public function setKnowledge(?string $knowledge): void
    {
        $this->knowledge = $knowledge;
    }

    public function getIntelligence(): ?int
    {
        return $this->intelligence;
    }

    public function setIntelligence(?int $intelligence): void
    {
        $this->intelligence = $intelligence;
    }

    public function getStrength(): ?int
    {
        return $this->strength;
    }

    public function setStrength(?int $strength): void
    {
        $this->strength = $strength;
    }

    public function getLife(): ?int
    {
        return $this->life;
    }

    public function setLife(?int $life): void
    {
        $this->life = $life;
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

    public function setModification(DateTimeImmutable $modification): self
    {
        $this->modification = $modification;

        return $this;
    }

    public function getBuilding(): ?Building
    {
        return $this->building;
    }

    public function setBuilding(?Building $building): self
    {
        $this->building = $building;

        return $this;
    }

    public function getCastle(): ?Building
    {
        return $this->building;
    }

    public function setCastle(?Building $castle): void
    {
        $this->building = $castle;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

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

}
