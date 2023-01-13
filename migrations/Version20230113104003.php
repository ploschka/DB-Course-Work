<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230113104003 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE receiving DROP FOREIGN KEY FK_F7E831B2533AD253');
        $this->addSql('ALTER TABLE receiving DROP FOREIGN KEY FK_F7E831B26B20BA36');
        $this->addSql('ALTER TABLE receiving ADD CONSTRAINT FK_F7E831B2533AD253 FOREIGN KEY (work_clothing_id) REFERENCES work_clothing (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE receiving ADD CONSTRAINT FK_F7E831B26B20BA36 FOREIGN KEY (worker_id) REFERENCES worker (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE worker DROP FOREIGN KEY FK_9FB2BF624B89032C');
        $this->addSql('ALTER TABLE worker DROP FOREIGN KEY FK_9FB2BF62AE80F5DF');
        $this->addSql('ALTER TABLE worker ADD CONSTRAINT FK_9FB2BF624B89032C FOREIGN KEY (post_id) REFERENCES post (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE worker ADD CONSTRAINT FK_9FB2BF62AE80F5DF FOREIGN KEY (department_id) REFERENCES department (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE worker DROP FOREIGN KEY FK_9FB2BF624B89032C');
        $this->addSql('ALTER TABLE worker DROP FOREIGN KEY FK_9FB2BF62AE80F5DF');
        $this->addSql('ALTER TABLE worker ADD CONSTRAINT FK_9FB2BF624B89032C FOREIGN KEY (post_id) REFERENCES post (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE worker ADD CONSTRAINT FK_9FB2BF62AE80F5DF FOREIGN KEY (department_id) REFERENCES department (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE receiving DROP FOREIGN KEY FK_F7E831B26B20BA36');
        $this->addSql('ALTER TABLE receiving DROP FOREIGN KEY FK_F7E831B2533AD253');
        $this->addSql('ALTER TABLE receiving ADD CONSTRAINT FK_F7E831B26B20BA36 FOREIGN KEY (worker_id) REFERENCES worker (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE receiving ADD CONSTRAINT FK_F7E831B2533AD253 FOREIGN KEY (work_clothing_id) REFERENCES work_clothing (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
