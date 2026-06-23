<?php

namespace App\Service;

use App\DTO\ContainerStatsDTO;
use App\DTO\DockerStatsDTO;
use App\Repository\ContainerRepository;
use App\Repository\ContainerStatsRepository;
use App\Repository\ContainerTypeRepository;
use App\Repository\ProjectRepository;
use Symfony\Component\Serializer\SerializerInterface;

readonly class DockerService
{
    public function __construct(
        private SerializerInterface $serializer,
        private ProjectRepository $projectRepository,
        private ContainerTypeRepository $containerTypeRepository,
        private ContainerRepository $containerRepository,
        private ContainerStatsRepository $containerStatsRepository,
    )
    {
    }

    public function getDockerStatsRaw(): string|array
    {
        $chandler = curl_init();
        curl_setopt($chandler, CURLOPT_UNIX_SOCKET_PATH, "/var/run/docker.sock");
        curl_setopt($chandler, CURLOPT_URL, "http://localhost/containers/json?all=1");
        curl_setopt($chandler, CURLOPT_RETURNTRANSFER, true);

        $jsonResponse = curl_exec($chandler);

        if (!$jsonResponse) {
            $error = curl_error($chandler);
            curl_close($chandler);
            return ['error' => 'Errore connessione Socket: ' . $error];
        }

        curl_close($chandler);

        return $jsonResponse;
    }

    public function getDockerStatsDTO(): array
    {
        $jsonResponse = $this->getDockerStatsRaw();

        if (is_array($jsonResponse) && isset($jsonResponse['error'])) {
            return $jsonResponse;
        }

        $dockerStatsDTO = $this->serializer->deserialize(
            $jsonResponse,
            DockerStatsDTO::class . '[]',
            'json'
        );

        $this->addStatsDB($dockerStatsDTO);

        return $dockerStatsDTO;
    }

    public function addStatsDB(array $dockerStatsDTO): void
    {
        $this->projectRepository->saveFromDTOToProject($dockerStatsDTO);

        $this->containerTypeRepository->saveFromDTOToContainerType($dockerStatsDTO);

        $this->containerRepository->saveFromDTOToContainer($dockerStatsDTO);

        $this->containerStatsRepository->saveFromDTOToContainerStats($dockerStatsDTO);
    }

    public function getStatsDB() : ?array
    {
        $containerStats = $this->containerStatsRepository->getAllStats();

        $data = [];
        foreach ($containerStats as $containerStat) {
            $data[] = ContainerStatsDTO::EntityToDTO($containerStat);
        }

        return $data;
    }
}
