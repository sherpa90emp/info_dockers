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
    name: 'DockerStatsCommand',
    description: 'Recupera le statistiche dei container Docker',
)]
class DockerStatsCommand extends Command
{
    public function __construct(
        private DockerService $dockerService,
        private EntityManagerInterface $entityManager
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $containerStats = $this->dockerService->getDockerStats();

        if (isset($containerStats['error'])) {
            $io->error($containerStats['error']);
            return Command::FAILURE;
        }

        $rows = [];
        foreach ($containerStats as $stats) {
            $rows[] = [
                $stats['Name'][0] ?? 'N/A'
            ];
        }

        $io->table(
            ['Nome container'],
            $rows
        );

        $io->success('Dati recuperati con successo');

        return Command::SUCCESS;
    }
}
