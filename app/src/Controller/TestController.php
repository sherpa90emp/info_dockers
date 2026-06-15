<?php

namespace App\Controller;

use App\Service\DockerService;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TestController extends AbstractController
{

    /**
     * Route di test per verificare l'integrazione con DockerService.
     * Ottiene statistiche raw e DTO, le formatta e le debugga.
     *
     * @param DockerService $dockerService Servizio per l'interfaccia Docker
     * @return Response
     */
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

    /**
     * Formatta i dati dei container in una struttura tabellare.
     * Estrae informazioni essenziali da DockerStatsDTO.
     *
     * @param array $test Array di DockerStatsDTO
     * @return array Struttura tabellare con: id, nome formattato, stato numerico, stato, salute
     */
    private function stampaTest($test): array
    {
        $rows = [];
        foreach ($test as $statsDTO) {
            $rows[] = [
                $statsDTO->id,
                $statsDTO->getCleanNames(),
                $statsDTO->getCleanIntStatus(),
                $statsDTO->state,
                $statsDTO->getHealthStatus()
            ];
        }

        return $rows;
    }
}
