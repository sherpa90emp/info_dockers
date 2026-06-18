<?php

namespace App\Repository;

use App\Entity\Project;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Project>
 */
class ProjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Project::class);
    }

    public function saveFromDTOToProject(array $dockerStatsDTO) : void
    {
        $entityManager = $this->getEntityManager();

        foreach ($dockerStatsDTO as $dockerStatDTO) {
            $projectName = $dockerStatDTO->getProjectName();

            $project = $this->findOneBy(['projectName' => $projectName]);

            if (!$project) {
                $project = new Project();
                $project->setProjectName($projectName);
                $project->setPath($dockerStatDTO->getPath());
                $entityManager->persist($project);
            } else {
                $project->setPath($dockerStatDTO->getPath());
            }
        }

        $entityManager->flush();
    }
}
