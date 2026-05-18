<?php

namespace App\Controller;

use App\Service\DockerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TestController extends AbstractController
{

    #[Route('/test', name: 'app_test')]
    public function test(DockerService $dockerService): Response
    {
        $test = $dockerService->getDockerStats();

        dd($test);

        return $this->render('/test', [
            'containers' => $test,
        ]);
    }
}
