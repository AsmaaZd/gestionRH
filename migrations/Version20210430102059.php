<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210430102059 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE candidat ADD profil_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE candidat ADD CONSTRAINT FK_6AB5B471275ED078 FOREIGN KEY (profil_id) REFERENCES profil (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6AB5B471275ED078 ON candidat (profil_id)');
        $this->addSql('ALTER TABLE profil DROP FOREIGN KEY FK_E6D6B2978D0EB82');
        $this->addSql('ALTER TABLE profil DROP FOREIGN KEY FK_E6D6B297BB0859F1');
        $this->addSql('DROP INDEX UNIQ_E6D6B297BB0859F1 ON profil');
        $this->addSql('DROP INDEX UNIQ_E6D6B2978D0EB82 ON profil');
        $this->addSql('ALTER TABLE profil DROP candidat_id, DROP recruteur_id');
        $this->addSql('ALTER TABLE recruteur ADD profil_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE recruteur ADD CONSTRAINT FK_2BD3678C275ED078 FOREIGN KEY (profil_id) REFERENCES profil (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2BD3678C275ED078 ON recruteur (profil_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE candidat DROP FOREIGN KEY FK_6AB5B471275ED078');
        $this->addSql('DROP INDEX UNIQ_6AB5B471275ED078 ON candidat');
        $this->addSql('ALTER TABLE candidat DROP profil_id');
        $this->addSql('ALTER TABLE profil ADD candidat_id INT DEFAULT NULL, ADD recruteur_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE profil ADD CONSTRAINT FK_E6D6B2978D0EB82 FOREIGN KEY (candidat_id) REFERENCES candidat (id)');
        $this->addSql('ALTER TABLE profil ADD CONSTRAINT FK_E6D6B297BB0859F1 FOREIGN KEY (recruteur_id) REFERENCES recruteur (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E6D6B297BB0859F1 ON profil (recruteur_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E6D6B2978D0EB82 ON profil (candidat_id)');
        $this->addSql('ALTER TABLE recruteur DROP FOREIGN KEY FK_2BD3678C275ED078');
        $this->addSql('DROP INDEX UNIQ_2BD3678C275ED078 ON recruteur');
        $this->addSql('ALTER TABLE recruteur DROP profil_id');
    }
}
