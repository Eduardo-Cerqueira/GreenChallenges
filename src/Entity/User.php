<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $surname = null;

    #[ORM\Column(length: 255)]
    private ?string $username = null;

    #[ORM\Column(length: 255)]
    private ?string $country = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $last_connection = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\OneToMany(mappedBy: 'created_by', targetEntity: Challenge::class)]
    private Collection $challenges;

    #[ORM\OneToMany(mappedBy: 'user_uuid', targetEntity: CurrentChallenge::class)]
    private Collection $currentChallenges;

    #[ORM\Column(type: 'uuid')]
    private ?Uuid $uuid = null;

    public function __construct()
    {
        $this->challenges = new ArrayCollection();
        $this->currentChallenges = new ArrayCollection();
        $this->setUuid(Uuid::v6());
        $this->setCreatedAt(new \DateTimeImmutable("now", new \DateTimeZone('Europe/Paris')));
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
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

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): static
    {
        $this->surname = $surname;

        return $this;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function getLastConnection(): ?\DateTimeInterface
    {
        return $this->last_connection;
    }

    public function setLastConnection(?\DateTimeInterface $last_connection): static
    {
        $this->last_connection = $last_connection;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * @return Collection<int, Challenge>
     */
    public function getChallenges(): Collection
    {
        return $this->challenges;
    }

    public function addChallenge(Challenge $challenge): static
    {
        if (!$this->challenges->contains($challenge)) {
            $this->challenges->add($challenge);
            $challenge->setCreatedBy($this);
        }

        return $this;
    }

    public function removeChallenge(Challenge $challenge): static
    {
        if ($this->challenges->removeElement($challenge)) {
            // set the owning side to null (unless already changed)
            if ($challenge->getCreatedBy() === $this) {
                $challenge->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CurrentChallenge>
     */
    public function getCurrentChallenges(): Collection
    {
        return $this->currentChallenges;
    }

    public function addCurrentChallenge(CurrentChallenge $currentChallenge): static
    {
        if (!$this->currentChallenges->contains($currentChallenge)) {
            $this->currentChallenges->add($currentChallenge);
            $currentChallenge->setUserUuid($this);
        }

        return $this;
    }

    public function removeCurrentChallenge(CurrentChallenge $currentChallenge): static
    {
        if ($this->currentChallenges->removeElement($currentChallenge)) {
            // set the owning side to null (unless already changed)
            if ($currentChallenge->getUserUuid() === $this) {
                $currentChallenge->setUserUuid(null);
            }
        }

        return $this;
    }

    public function getUuid(): ?Uuid
    {
        return $this->uuid;
    }

    public function setUuid(Uuid $uuid): static
    {
        $this->uuid = $uuid;

        return $this;
    }
}
