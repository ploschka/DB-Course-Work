<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221209071316 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE department CHANGE name name VARCHAR(50) NOT NULL, CHANGE chief_name chief_name VARCHAR(50) NOT NULL, ADD CONSTRAINT CHK_DepStrLength CHECK (LENGTH(name)>=5 AND LENGTH(chief_name)>=5)');
        $this->addSql('ALTER TABLE post CHANGE name name VARCHAR(50) NOT NULL, ADD CONSTRAINT CHK_PostStrLength CHECK (LENGTH(name)>=5)');
        $this->addSql('ALTER TABLE work_clothing CHANGE type type VARCHAR(100) NOT NULL, ADD CONSTRAINT CHK_WorkClothStrLength CHECK (LENGTH(type)>=4)');
        $this->addSql('ALTER TABLE worker CHANGE name name VARCHAR(50) NOT NULL, ADD CONSTRAINT CHK_WorkerStrLength CHECK (LENGTH(name)>=5)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE department CHANGE name name VARCHAR(255) NOT NULL, CHANGE chief_name chief_name VARCHAR(255) NOT NULL, DROP CHECK CHK_DepStrLength');
        $this->addSql('ALTER TABLE worker CHANGE name name VARCHAR(255) NOT NULL, DROP CHECK CHK_WorkerStrLength');
        $this->addSql('ALTER TABLE work_clothing CHANGE type type VARCHAR(255) NOT NULL, DROP CHECK CHK_WorkClothStrLength');
        $this->addSql('ALTER TABLE post CHANGE name name VARCHAR(255) NOT NULL, DROP CHECK CHK_PostStrLength');
    }
}
