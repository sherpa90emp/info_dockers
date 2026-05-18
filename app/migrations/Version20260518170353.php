<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260518170353 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE container (id INT AUTO_INCREMENT NOT NULL, docker_id INT NOT NULL, name VARCHAR(50) NOT NULL, state VARCHAR(50) NOT NULL, duration INT NOT NULL, health VARCHAR(50) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE container_type (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(50) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE project (id INT AUTO_INCREMENT NOT NULL, project_name VARCHAR(50) NOT NULL, path VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE server (id INT AUTO_INCREMENT NOT NULL, server_name VARCHAR(255) NOT NULL, ip_address VARCHAR(255) NOT NULL, total_ram INT NOT NULL, total_cpu INT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE container');
        $this->addSql('DROP TABLE container_type');
        $this->addSql('DROP TABLE project');
        $this->addSql('DROP TABLE server');
    }
}
