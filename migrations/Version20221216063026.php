<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221216063026 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE department ADD chief_id INT NOT NULL, DROP chief_name');
        $this->addSql('ALTER TABLE department ADD CONSTRAINT FK_CD1DE18A7A7B68E1 FOREIGN KEY (chief_id) REFERENCES worker (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CD1DE18A7A7B68E1 ON department (chief_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE department DROP FOREIGN KEY FK_CD1DE18A7A7B68E1');
        $this->addSql('DROP INDEX UNIQ_CD1DE18A7A7B68E1 ON department');
        $this->addSql('ALTER TABLE department ADD chief_name VARCHAR(50) NOT NULL, DROP chief_id');
    }
}
