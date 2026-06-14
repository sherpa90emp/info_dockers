<?php

namespace App\Controller;

use App\Service\DockerService;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TestController extends AbstractController
{

    #[NoReturn]
    #[Route('/test', name: 'app_test')]
    public function test(DockerService $dockerService): Response
    {
        $raw = $dockerService->getDockerStatsRaw();

        $rawStampa = json_decode($raw, true);

        $test = $dockerService->getDockerStatsDTO();

        $risposta = $this->stampaTest($test);

        dd($rawStampa, $test, $risposta);
    }

    private function stampaTest($test): array
    {
        $rows = [];
        foreach ($test as $statsDTO) {
            $rows[] = [
                $statsDTO->id,
                $statsDTO->getCleanNames(),
                $statsDTO->getCleanStatus(),
                $statsDTO->state,
                $statsDTO->getHealthStatus()
            ];
        }

        return $rows;
    }
}
