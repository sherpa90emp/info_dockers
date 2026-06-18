<?php

namespace App\Repository;

use App\Entity\ContainerType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ContainerType>
 */
class ContainerTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ContainerType::class);
    }

    public function saveFromDTOToContainerType(array $dockerStatsDTO) : void
    {
        $entityManager = $this->getEntityManager();

        foreach ($dockerStatsDTO as $dockerStatDTO) {
            $serviceName = $dockerStatDTO->getServiceName();

            $containerType = $this->findOneBy(['label' => $serviceName]);

            if (!$containerType) {
                $containerType = new ContainerType();
                $containerType->setLabel($serviceName);
                $entityManager->persist($containerType);

            }
        }

        $entityManager->flush();
    }
}
