<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221201080332 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE department (id INT AUTO_INCREMENT NOT NULL, department_id INT NOT NULL, name VARCHAR(255) NOT NULL, chief_name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post (id INT AUTO_INCREMENT NOT NULL, post_id INT NOT NULL, name VARCHAR(255) NOT NULL, discount SMALLINT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE receiving (id INT AUTO_INCREMENT NOT NULL, worker_id INT NOT NULL, work_clothing_id INT NOT NULL, date DATE NOT NULL, signature VARCHAR(255) NOT NULL, INDEX IDX_F7E831B26B20BA36 (worker_id), UNIQUE INDEX UNIQ_F7E831B2533AD253 (work_clothing_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE work_clothing (id INT AUTO_INCREMENT NOT NULL, clothing_id VARCHAR(6) NOT NULL, type VARCHAR(255) NOT NULL, wear_time SMALLINT NOT NULL, price DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE worker (id INT AUTO_INCREMENT NOT NULL, post_id INT NOT NULL, department_id INT NOT NULL, worker_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_9FB2BF624B89032C (post_id), INDEX IDX_9FB2BF62AE80F5DF (department_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE receiving ADD CONSTRAINT FK_F7E831B26B20BA36 FOREIGN KEY (worker_id) REFERENCES worker (id)');
        $this->addSql('ALTER TABLE receiving ADD CONSTRAINT FK_F7E831B2533AD253 FOREIGN KEY (work_clothing_id) REFERENCES work_clothing (id)');
        $this->addSql('ALTER TABLE worker ADD CONSTRAINT FK_9FB2BF624B89032C FOREIGN KEY (post_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE worker ADD CONSTRAINT FK_9FB2BF62AE80F5DF FOREIGN KEY (department_id) REFERENCES department (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE receiving DROP FOREIGN KEY FK_F7E831B26B20BA36');
        $this->addSql('ALTER TABLE receiving DROP FOREIGN KEY FK_F7E831B2533AD253');
        $this->addSql('ALTER TABLE worker DROP FOREIGN KEY FK_9FB2BF624B89032C');
        $this->addSql('ALTER TABLE worker DROP FOREIGN KEY FK_9FB2BF62AE80F5DF');
        $this->addSql('DROP TABLE department');
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE receiving');
        $this->addSql('DROP TABLE work_clothing');
        $this->addSql('DROP TABLE worker');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
