<?php

namespace App\Entity;


use App\Repository\ContainerRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContainerRepository::class)]
class Container
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?string $dockerId = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\ManyToOne(targetEntity: Project::class, inversedBy: 'containers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Project $project = null;

    #[ORM\ManyToOne(targetEntity: ContainerType::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?ContainerType $type = null;

    #[ORM\OneToMany(targetEntity: ContainerStats::class, mappedBy: 'container')]
    private Collection $stats;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getDockerId(): ?string
    {
        return $this->dockerId;
    }

    public function setDockerId(?string $dockerId): void
    {
        $this->dockerId = $dockerId;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): void
    {
        $this->project = $project;
    }

    public function getType(): ?ContainerType
    {
        return $this->type;
    }

    public function setType(?ContainerType $type): void
    {
        $this->type = $type;
    }

    public function getStats(): Collection
    {
        return $this->stats;
    }

    public function setStats(Collection $stats): void
    {
        $this->stats = $stats;
    }
}
