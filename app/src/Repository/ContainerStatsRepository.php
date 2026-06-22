<?php

namespace App\Repository;

use App\Entity\Container;
use App\Entity\ContainerStats;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ContainerStats>
 */
class ContainerStatsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ContainerStats::class);
    }

    public function saveFromDTOToContainerStats(array $dockerStatsDTO) : void
    {
        $entityManager = $this->getEntityManager();
        $containerRepo = $entityManager->getRepository(Container::class);

        foreach ($dockerStatsDTO as $dockerStatDTO) {
            $containerStats = new ContainerStats();
            $containerStats->setState($dockerStatDTO->state);
            $containerStats->setStatus($dockerStatDTO->status);
            $containerStats->setDuration($dockerStatDTO->getCleanIntStatus());
            $containerStats->setHealth($dockerStatDTO->getHealthStatus());
            $containerStats->setCreatedAt(new \DateTimeImmutable());

            $containerEntity = $containerRepo->findOneBy(['dockerId' => $dockerStatDTO->id]);

            if ($containerEntity) {
                $containerStats->setContainer($containerEntity);
            }

            $entityManager->persist($containerStats);
        }

        $entityManager->flush();
    }

    /**
     * Ottiene tutte le statistiche dei container.
     *
     * @return ContainerStats[]|null
     */
    public function getAllStats() : ?array
    {
        return $this->findAll();
    }
}
