<?php

namespace App\DTO;

use Symfony\Component\Serializer\Attribute\SerializedName;

readonly class DockerStatsDTO
{
    public function __construct(
        #[SerializedName('Id')]
        public string $id,

        #[SerializedName('Names')]
        public array  $names,

        #[SerializedName('Status')]
        public string $status,

        #[SerializedName('State')]
        public string $state,

        #[SerializedName('Health')]
        public ?array $health
    )
    {}

    public function getCleanNames() : string
    {
        $name =  isset($this->names[0]) ? ltrim($this->names[0], '/') : 'N/A';

        return str_replace('_', ' ', $name);
    }

    public function getHealthStatus() : string
    {
        return $this->health['Status'] ?? 'N/A';
    }
}
