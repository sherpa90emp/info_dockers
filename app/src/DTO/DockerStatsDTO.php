<?php

namespace App\DTO;

use Symfony\Component\Serializer\Attribute\SerializedName;

class DockerStatsDTO
{
    public function __construct(
        #[SerializedName('Id')]
        public readonly string $id,

        #[SerializedName('Names')]
        public readonly array $names,

        #[SerializedName('Status')]
        public readonly string $status,

        #[SerializedName('State')]
        public readonly string $state,

        #[SerializedName('Health')]
        public readonly string $health
    )
    {}

    public function getCleanNames() : string
    {
        $name =  isset($this->names[0]) ? ltrim($this->names[0], '/') : 'N/A';

        return str_replace('_', ' ', $name);
    }
}
