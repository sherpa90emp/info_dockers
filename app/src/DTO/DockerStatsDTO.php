<?php

namespace App\DTO;

class DockerStatsDTO
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly string $state,
        public readonly string $status,
        public readonly string $health
    )
    {}
}
