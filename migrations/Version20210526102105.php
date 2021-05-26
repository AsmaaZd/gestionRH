<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210526102105 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE visioconference (id INT AUTO_INCREMENT NOT NULL, entretien_id INT DEFAULT NULL, lien VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_D2880243548DCEA2 (entretien_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE visioconference ADD CONSTRAINT FK_D2880243548DCEA2 FOREIGN KEY (entretien_id) REFERENCES entretien (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE visioconference');
    }
}
