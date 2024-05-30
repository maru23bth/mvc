<?php

namespace App\Entity;

use App\Repository\HighScorePointsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HighScorePointsRepository::class)]
class HighScorePoints
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $score = null;

    #[ORM\ManyToOne(inversedBy: 'scores')]
    #[ORM\JoinColumn(nullable: false)]
    private ?HighScoreUser $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(int $score): static
    {
        $this->score = $score;

        return $this;
    }

    public function getUser(): ?HighScoreUser
    {
        return $this->user;
    }

    public function setUser(?HighScoreUser $user): static
    {
        $this->user = $user;

        return $this;
    }
}
