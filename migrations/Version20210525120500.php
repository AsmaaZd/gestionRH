<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210525120500 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE dispo_salle (id INT AUTO_INCREMENT NOT NULL, salle_id INT DEFAULT NULL, date DATETIME NOT NULL, INDEX IDX_CB951A03DC304035 (salle_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE dispo_salle ADD CONSTRAINT FK_CB951A03DC304035 FOREIGN KEY (salle_id) REFERENCES salle (id)');
        $this->addSql('ALTER TABLE entretien ADD salle_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE entretien ADD CONSTRAINT FK_2B58D6DADC304035 FOREIGN KEY (salle_id) REFERENCES salle (id)');
        $this->addSql('CREATE INDEX IDX_2B58D6DADC304035 ON entretien (salle_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE dispo_salle');
        $this->addSql('ALTER TABLE entretien DROP FOREIGN KEY FK_2B58D6DADC304035');
        $this->addSql('DROP INDEX IDX_2B58D6DADC304035 ON entretien');
        $this->addSql('ALTER TABLE entretien DROP salle_id');
    }
}
