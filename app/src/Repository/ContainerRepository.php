<?php

namespace App\Repository;

use App\Entity\Container;
use App\Entity\ContainerType;
use App\Entity\Project;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Container>
 */
class ContainerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Container::class);
    }

    public function saveFromDTOToContainer(array $dockerStatsDTO) : void
    {
        $entityManager = $this->getEntityManager();
        $projectRepo = $entityManager->getRepository(Project::class);
        $typeRepo = $entityManager->getRepository(ContainerType::class);



        foreach ($dockerStatsDTO as $dockerStatDTO) {
            $dockerId = $dockerStatDTO->id;

            $container = $this->findOneBy(['dockerId' => $dockerId]);

            if (!$container) {
                $container = new Container();
                $container->setDockerId($dockerId);
                $container->setName($dockerStatDTO->getCleanNames());

                $project = $projectRepo->findOneBy(['projectName' => $dockerStatDTO->getProjectName()]);
                if ($project) {
                    $container->setProject($project);
                }

                $type = $typeRepo->findOneBy(['label' => $dockerStatDTO->getServicename()]);
                if ($type) {
                    $container->setType($type);
                }

                $entityManager->persist($container);
            }
        }

        $entityManager->flush();
    }
}
