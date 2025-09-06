<?php

namespace App\Entity;

use App\Repository\PollQuestionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PollQuestionRepository::class)]
class PollQuestion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $pollNumber = null;

    #[ORM\Column]
    private ?int $pollQuestionId = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $jsonData = null;

    #[ORM\Column]
    private ?int $answered = 2;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPollNumber(): ?int
    {
        return $this->pollNumber;
    }

    public function setPollNumber(int $pollNumber): static
    {
        $this->pollNumber = $pollNumber;

        return $this;
    }

    public function getPollQuestionId(): ?int
    {
        return $this->pollQuestionId;
    }

    public function setPollQuestionId(int $pollQuestionId): static
    {
        $this->pollQuestionId = $pollQuestionId;

        return $this;
    }

    public function getJsonData(): ?string
    {
        return $this->jsonData;
    }

    public function setJsonData(string $jsonData): static
    {
        $this->jsonData = $jsonData;

        return $this;
    }

    public function isAnswered(): bool
    {
        return $this->answered == 1;
    }

    public function setAnswered(?int $answered): void
    {
        // 2 not touched
        // 1 answered
        // 0 not answered

        $this->answered = $answered;
    }
}
