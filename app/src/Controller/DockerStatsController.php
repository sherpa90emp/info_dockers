<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/docker-stats', name: 'app_docker_stats')]
class DockerStatsController extends AbstractController
{
    public function __construct(

    )
    {

    }
}
