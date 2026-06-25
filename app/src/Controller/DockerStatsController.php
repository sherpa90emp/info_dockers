<?php

namespace App\Controller;

use App\Entity\ContainerStats;
use App\Service\DockerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/docker-stats', name: 'docker_stats')]
class DockerStatsController extends AbstractController
{
    public function __construct(
        private readonly SerializerInterface $serializer,
    )
    {
    }

    #[Route('', name: '')]
    public function getDockerStats() : Response
    {
        return $this->render('docker-stats/docker-stats.twig');
    }

    #[Route('/tabella', name: 'docker_stats.tabella', methods: ['GET'])]
    public function getContainerStats(DockerService $dockerService) : Response
    {
        $stats = $dockerService->getStatsDB();

        try {
            return new Response($this->serializer->serialize($stats, 'json', [ContainerStats::class]), Response::HTTP_OK);
        } catch (ExceptionInterface $e) {
            return new Response($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
