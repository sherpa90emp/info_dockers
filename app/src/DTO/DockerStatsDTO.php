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
        public ?array $health,

        #[SerializedName('Labels')]
        public ?array $labels
    )
    {}

    /**
     * Formatta il nome del container rimuovendo il prefisso '/' e sostituendo
     * underscore/hyphen con spazi per una migliore leggibilità
     */
    public function getCleanNames() : string
    {
        $name =  isset($this->names[0]) ? ltrim($this->names[0], '/') : 'N/A';

        return str_replace(['_', '-'], ' ', $name);
    }

    /**
     * Recupera lo stato di salute del container (es. "healthy", "unhealthy")
     * Ritorna "N/A" se il campo health non è definito
     */
    public function getHealthStatus() : string
    {
        return $this->health['Status'] ?? 'N/A';
    }

    /**
     * Estrae il valore numerico dal campo Status (es. "Up 2 hours" → 2)
     * Rimuove tutti i caratteri non numerici e converte in intero
     */
    public function getCleanIntStatus()
    {
        $status = $this->status;

        return (int)(preg_replace('/\D/', '', $status));
    }

    public function getServiceName() : string
    {
        return $this->labels['com.docker.compose.service'] ?? 'N/A';
    }

    public function getProjectName()
    {
        return $this->labels['com.docker.compose.project'] ?? 'N/A';
    }

    public function getPath()
    {
        return $this->labels['com.docker.compose.project.working_dir'] ?? 'N/A';
    }
}
