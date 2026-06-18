<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260618231540 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE container_stats (id INT AUTO_INCREMENT NOT NULL, state VARCHAR(50) NOT NULL, status VARCHAR(50) NOT NULL, duration INT NOT NULL, health VARCHAR(20) NOT NULL, created_at DATETIME NOT NULL, container_id INT NOT NULL, INDEX IDX_2AC802FBBC21F742 (container_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE container_stats ADD CONSTRAINT FK_2AC802FBBC21F742 FOREIGN KEY (container_id) REFERENCES container (id)');
        $this->addSql('ALTER TABLE container DROP state, DROP duration, DROP health, CHANGE docker_id docker_id VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE container_stats DROP FOREIGN KEY FK_2AC802FBBC21F742');
        $this->addSql('DROP TABLE container_stats');
        $this->addSql('ALTER TABLE container ADD state VARCHAR(50) NOT NULL, ADD duration INT NOT NULL, ADD health VARCHAR(50) NOT NULL, CHANGE docker_id docker_id INT NOT NULL');
    }
}
