<?php

namespace App\DTO;

use App\Entity\ContainerStats;
use Symfony\Component\Serializer\Attribute\SerializedName;

readonly class ContainerStatsDTO
{
    public function __construct(
        #[SerializedName('Id')]
        public int $id,

        #[SerializedName('State')]
        public string $state,

        #[SerializedName('Status')]
        public string $status,

        #[SerializedName('Duration')]
        public int $duration,

        #[SerializedName('Health')]
        public string $health,

        #[SerializedName('Created')]
        public \DateTimeImmutable $createdAt,
    )
    {
    }


    public static function EntityToDTO(ContainerStats $containerStats) : ContainerStatsDTO
    {
        return new ContainerStatsDTO(
            $containerStats->getId(),
            $containerStats->getState(),
            $containerStats->getStatus(),
            $containerStats->getDuration(),
            $containerStats->getHealth(),
            $containerStats->getCreatedAt(),
        );
    }
}
