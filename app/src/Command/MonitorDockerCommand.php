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
    /**
     * Costruttore che inietta il servizio DockerService.
     *
     * @param DockerService $dockerService Servizio per l'interfaccia Docker
     */
    public function __construct(
        private readonly DockerService $dockerService,
    )
    {
        parent::__construct();
    }

    /**
     * Esegue il comando console per il monitoraggio Docker.
     * Recupera dati, verifica errori e visualizza i risultati.
     *
     * @param InputInterface $input Interfaccia di input del comando
     * @param OutputInterface $output Interfaccia di output del comando
     * @return int Codice di uscita (SUCCESS o FAILURE)
     */
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

    /**
     * Formatta i dati dei container in una struttura tabellare.
     *
     * @param array $containerStats Array di DockerStatsDTO
     * @param SymfonyStyle $io Interfaccia di output per stile Symfony
     * @return int Codice di uscita (SUCCESS)
     */
    private function stampaDatiContainer(array $containerStats, $io) : int
    {
        $rows = [];
        foreach ($containerStats as $statsDTO) {
            $rows[] = [
                $statsDTO->id,
                $statsDTO->getCleanNames(),
                $statsDTO->getCleanIntStatus(),
                $statsDTO->state,
                $statsDTO->getHealthStatus()
            ];
        }

        $io->success('Dati recuperati con successo alle ore: ');

        return Command::SUCCESS;
    }

    /**
     * Visualizza una tabella con i dati dei container.
     * (Da usare insieme a stampaDatiContainer per debug dati)
     *
     * @param array $rows Dati formattati in righe
     * @param SymfonyStyle $io Interfaccia di output per stile Symfony
     */
    private function debugTable($rows, $io)
    {
        $io->table(
            ['ID', 'Nome container', 'Status', 'State', 'Health'],
            $rows
        );
    }
}
