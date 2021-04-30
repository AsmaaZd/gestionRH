<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210430073456 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE candidat (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE competence (id INT AUTO_INCREMENT NOT NULL, competence VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE disponibilite (id INT AUTO_INCREMENT NOT NULL, recruteur_id INT DEFAULT NULL, date_dipro DATE NOT NULL, INDEX IDX_2CBACE2FBB0859F1 (recruteur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE entretien (id INT AUTO_INCREMENT NOT NULL, candidat_id INT NOT NULL, recruteur_id INT NOT NULL, date_entretien DATE NOT NULL, INDEX IDX_2B58D6DA8D0EB82 (candidat_id), INDEX IDX_2B58D6DABB0859F1 (recruteur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE profil (id INT AUTO_INCREMENT NOT NULL, candidat_id INT DEFAULT NULL, recruteur_id INT DEFAULT NULL, nb_annees_exp INT NOT NULL, UNIQUE INDEX UNIQ_E6D6B2978D0EB82 (candidat_id), UNIQUE INDEX UNIQ_E6D6B297BB0859F1 (recruteur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE profil_competence (profil_id INT NOT NULL, competence_id INT NOT NULL, INDEX IDX_AF123381275ED078 (profil_id), INDEX IDX_AF12338115761DAB (competence_id), PRIMARY KEY(profil_id, competence_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recruteur (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE disponibilite ADD CONSTRAINT FK_2CBACE2FBB0859F1 FOREIGN KEY (recruteur_id) REFERENCES recruteur (id)');
        $this->addSql('ALTER TABLE entretien ADD CONSTRAINT FK_2B58D6DA8D0EB82 FOREIGN KEY (candidat_id) REFERENCES candidat (id)');
        $this->addSql('ALTER TABLE entretien ADD CONSTRAINT FK_2B58D6DABB0859F1 FOREIGN KEY (recruteur_id) REFERENCES recruteur (id)');
        $this->addSql('ALTER TABLE profil ADD CONSTRAINT FK_E6D6B2978D0EB82 FOREIGN KEY (candidat_id) REFERENCES candidat (id)');
        $this->addSql('ALTER TABLE profil ADD CONSTRAINT FK_E6D6B297BB0859F1 FOREIGN KEY (recruteur_id) REFERENCES recruteur (id)');
        $this->addSql('ALTER TABLE profil_competence ADD CONSTRAINT FK_AF123381275ED078 FOREIGN KEY (profil_id) REFERENCES profil (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE profil_competence ADD CONSTRAINT FK_AF12338115761DAB FOREIGN KEY (competence_id) REFERENCES competence (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE entretien DROP FOREIGN KEY FK_2B58D6DA8D0EB82');
        $this->addSql('ALTER TABLE profil DROP FOREIGN KEY FK_E6D6B2978D0EB82');
        $this->addSql('ALTER TABLE profil_competence DROP FOREIGN KEY FK_AF12338115761DAB');
        $this->addSql('ALTER TABLE profil_competence DROP FOREIGN KEY FK_AF123381275ED078');
        $this->addSql('ALTER TABLE disponibilite DROP FOREIGN KEY FK_2CBACE2FBB0859F1');
        $this->addSql('ALTER TABLE entretien DROP FOREIGN KEY FK_2B58D6DABB0859F1');
        $this->addSql('ALTER TABLE profil DROP FOREIGN KEY FK_E6D6B297BB0859F1');
        $this->addSql('DROP TABLE candidat');
        $this->addSql('DROP TABLE competence');
        $this->addSql('DROP TABLE disponibilite');
        $this->addSql('DROP TABLE entretien');
        $this->addSql('DROP TABLE profil');
        $this->addSql('DROP TABLE profil_competence');
        $this->addSql('DROP TABLE recruteur');
    }
}
