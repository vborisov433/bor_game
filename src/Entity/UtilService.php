<?php

namespace App\Entity;

use App\Repository\UtilServiceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UtilServiceRepository::class)]
class UtilService
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?\DateTime $lastUpdated = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $data = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getLastUpdated(): ?\DateTime
    {
        return $this->lastUpdated;
    }

    public function setLastUpdated(): static
    {
        $this->lastUpdated = new \DateTime();

        return $this;
    }

    public function getData()
    {
        return json_decode($this->data, true);
    }

    public function setData($data): static
    {
        $this->data = json_encode($data);

        return $this;
    }
}
