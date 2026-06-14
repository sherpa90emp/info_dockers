<?php

namespace App\Command;

use App\Service\DockerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:docker:monitor',
    description: 'Recupera le statistiche dei container Docker',
)]
class MonitorDockerCommand extends Command
{
    public function __construct(
        private readonly DockerService $dockerService,
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $containerStats = $this->dockerService->getDockerStatsDTO();

        if (isset($containerStats['error'])) {
            $io->error($containerStats['error']);
            return Command::FAILURE;
        }

        return $this->stampaDatiContainer($containerStats, $io);
    }

    private function stampaDatiContainer(array $containerStats, $io) : int
    {
        $rows = [];
        foreach ($containerStats as $statsDTO) {
            $rows[] = [
                $statsDTO->id,
                $statsDTO->getCleanNames(),
                $statsDTO->getCleanStatus(),
                $statsDTO->state,
                $statsDTO->getHealthStatus()
            ];
        }

        $io->success('Dati recuperati con successo alle ore: ');

        return Command::SUCCESS;
    }

    private function debugTable($rows, $io)
    {
        $io->table(
            ['ID', 'Nome container', 'Status', 'State', 'Health'],
            $rows
        );
    }
}
