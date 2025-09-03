<?php

namespace App\Entity;

use App\Repository\UploadedFileRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UploadedFileRepository::class)]
class UploadedFile
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?int $groupType = 1;

    #[ORM\Column(length: 255)]
    private ?string $path = null;

    /**
     * @param \DateTimeImmutable|null $createdAt
     */
    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getGroupType(): ?int
    {
        return $this->groupType;
    }

    public function setGroupType(int $groupType): static
    {
        $this->groupType = $groupType;

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): static
    {
        $this->path = $path;

        return $this;
    }
}
