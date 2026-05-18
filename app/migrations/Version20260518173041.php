<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260518173041 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE container ADD project_id INT NOT NULL, ADD type_id INT NOT NULL');
        $this->addSql('ALTER TABLE container ADD CONSTRAINT FK_C7A2EC1B166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE container ADD CONSTRAINT FK_C7A2EC1BC54C8C93 FOREIGN KEY (type_id) REFERENCES container_type (id)');
        $this->addSql('CREATE INDEX IDX_C7A2EC1B166D1F9C ON container (project_id)');
        $this->addSql('CREATE INDEX IDX_C7A2EC1BC54C8C93 ON container (type_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE container DROP FOREIGN KEY FK_C7A2EC1B166D1F9C');
        $this->addSql('ALTER TABLE container DROP FOREIGN KEY FK_C7A2EC1BC54C8C93');
        $this->addSql('DROP INDEX IDX_C7A2EC1B166D1F9C ON container');
        $this->addSql('DROP INDEX IDX_C7A2EC1BC54C8C93 ON container');
        $this->addSql('ALTER TABLE container DROP project_id, DROP type_id');
    }
}
