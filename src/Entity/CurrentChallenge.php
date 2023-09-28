<?php

namespace App\Entity;

use App\Repository\CurrentChallengeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: CurrentChallengeRepository::class)]
class CurrentChallenge
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;
    
    #[ORM\Column]
    private ?int $points = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;
    
    #[ORM\ManyToOne(inversedBy: 'currentChallenges')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Challenge $challenge_id = null;

    #[ORM\ManyToOne(inversedBy: 'currentChallenges')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user_uuid = null;

    #[ORM\Column(type: 'uuid')]
    private ?Uuid $uuid = null;

    public function __construct()
    {
        $this->setUuid(Uuid::v6());
        $this->setCreatedAt(new \DateTimeImmutable("now"));
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

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

    public function getPoints(): ?int
    {
        return $this->points;
    }

    public function setPoints(int $points): static
    {
        $this->points = $points;

        return $this;
    }

    public function getUserUuid(): ?User
    {
        return $this->user_uuid;
    }

    public function setUserUuid(?User $user_uuid): static
    {
        $this->user_uuid = $user_uuid;

        return $this;
    }

    public function getChallengeId(): ?Challenge
    {
        return $this->challenge_id;
    }

    public function setChallengeId(?Challenge $challenge_id): static
    {
        $this->challenge_id = $challenge_id;

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
