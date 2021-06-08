<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210608150917 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649BB0859F1');
        $this->addSql('DROP INDEX UNIQ_8D93D649BB0859F1 ON user');
        $this->addSql('ALTER TABLE user DROP recruteur_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD recruteur_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649BB0859F1 FOREIGN KEY (recruteur_id) REFERENCES recruteur (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649BB0859F1 ON user (recruteur_id)');
    }
}
