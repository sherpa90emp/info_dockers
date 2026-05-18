<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    public function getDockerStats(): array
    {
        $chandler = curl_init();
        curl_setopt($chandler, CURLOPT_UNIX_SOCKET_PATH, "/var/run/docker.sock");
        curl_setopt($chandler, CURLOPT_URL, "http://localhost/containers/json?all=1");
        curl_setopt($chandler, CURLOPT_RETURNTRANSFER, true);

        $jsonResponse = curl_exec($chandler);

        if ($jsonResponse === false) {
            $error = curl_error($chandler);
            curl_close($chandler);
            return ['error' => 'Errore connessione Socket: ' . $error];
        }

        curl_close($chandler);

        return json_decode($jsonResponse, true);
    }

    #[Route('/test', name: 'app_test')]
    public function test(): Response
    {
        $test = $this->getDockerStats();

        dd($test);

        return $this->render('/test', [
            'containers' => $test,
        ]);
    }
}
