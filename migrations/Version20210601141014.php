<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210601141014 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE entretien DROP FOREIGN KEY FK_2B58D6DAC5C89FEB');
        $this->addSql('DROP INDEX UNIQ_2B58D6DAC5C89FEB ON entretien');
        $this->addSql('ALTER TABLE entretien DROP visioconference_id');
        $this->addSql('ALTER TABLE visioconference ADD entretien_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE visioconference ADD CONSTRAINT FK_D2880243548DCEA2 FOREIGN KEY (entretien_id) REFERENCES entretien (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D2880243548DCEA2 ON visioconference (entretien_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE entretien ADD visioconference_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE entretien ADD CONSTRAINT FK_2B58D6DAC5C89FEB FOREIGN KEY (visioconference_id) REFERENCES visioconference (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2B58D6DAC5C89FEB ON entretien (visioconference_id)');
        $this->addSql('ALTER TABLE visioconference DROP FOREIGN KEY FK_D2880243548DCEA2');
        $this->addSql('DROP INDEX UNIQ_D2880243548DCEA2 ON visioconference');
        $this->addSql('ALTER TABLE visioconference DROP entretien_id');
    }
}
