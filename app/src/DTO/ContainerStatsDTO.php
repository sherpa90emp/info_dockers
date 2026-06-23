<?php

namespace App\DTO;

use Symfony\Component\Serializer\Attribute\SerializedName;

readonly class ContainerStatsDTO
{
    #[SerializedName('Id')]
    public int $id;

    #[SerializedName('State')]
    public string $state;

    #[SerializedName('Status')]
    public string $status;

    #[SerializedName('Duration')]
    public int $duration;

    #[SerializedName('Health')]
    public string $health;

    #[SerializedName('Created')]
    public \DateTimeImmutable $createdAt;
}
