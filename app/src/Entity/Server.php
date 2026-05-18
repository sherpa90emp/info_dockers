<?php

namespace App\Entity;

use App\Repository\ServerRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ServerRepository::class)]
class Server
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?string $serverName = null;

    #[ORM\Column]
    private ?string $ipAddress = null;

    #[ORM\Column]
    private ?int $totalRam = null;

    #[ORM\Column]
    private ?int $totalCpu = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getServerName(): ?string
    {
        return $this->serverName;
    }

    public function setServerName(?string $serverName): void
    {
        $this->serverName = $serverName;
    }

    public function getIpAddress(): ?string
    {
        return $this->ipAddress;
    }

    public function setIpAddress(?string $ipAddress): void
    {
        $this->ipAddress = $ipAddress;
    }

    public function getTotalRam(): ?int
    {
        return $this->totalRam;
    }

    public function setTotalRam(?int $totalRam): void
    {
        $this->totalRam = $totalRam;
    }

    public function getTotalCpu(): ?int
    {
        return $this->totalCpu;
    }

    public function setTotalCpu(?int $totalCpu): void
    {
        $this->totalCpu = $totalCpu;
    }


}
