<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Attribute\Ignore;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[ORM\HasLifecycleCallbacks]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['character', 'building', 'user'])]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    #[Groups(['character', 'building', 'user'])]
    private ?string $email = null;

    /**
     * @var list<string>
     */
    #[ORM\Column]
    #[Groups(['user'])]
    private array $roles = [];

    #[ORM\Column]
    #[Ignore]
    private ?string $password = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, name: 'gls_creation')]
    #[Groups(['user'])]
    private ?DateTimeImmutable $creation = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, name: 'gls_modification')]
    #[Groups(['user'])]
    private ?DateTimeImmutable $modification = null;

    /**
     * @var Collection<int, Character>
     */
    #[ORM\OneToMany(targetEntity: Character::class, mappedBy: 'user')]
    #[Groups(['user'])]
    private Collection $characters;

    public function __construct()
    {
        $this->creation = new DateTimeImmutable();
        $this->characters = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_values(array_unique($roles));
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function eraseCredentials(): void
    {
    }

    public function getCreation(): ?DateTimeImmutable
    {
        return $this->creation;
    }

    public function setCreation(DateTimeImmutable $creation): static
    {
        $this->creation = $creation;

        return $this;
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

    public function addCharacter(Character $character): static
    {
        if (!$this->characters->contains($character)) {
            $this->characters->add($character);
            $character->setUser($this);
        }

        return $this;
    }

    public function removeCharacter(Character $character): static
    {
        if ($this->characters->removeElement($character)) {
            if ($character->getUser() === $this) {
                $character->setUser(null);
            }
        }

        return $this;
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
