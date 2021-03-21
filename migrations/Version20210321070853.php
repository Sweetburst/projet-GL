<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210321070853 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE allergene_profil (allergene_id INT NOT NULL, profil_id INT NOT NULL, INDEX IDX_C1DEE16A4646AB2 (allergene_id), INDEX IDX_C1DEE16A275ED078 (profil_id), PRIMARY KEY(allergene_id, profil_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE scan (id INT AUTO_INCREMENT NOT NULL, nom_scan VARCHAR(255) NOT NULL, valeur_code_bar VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE allergene_profil ADD CONSTRAINT FK_C1DEE16A4646AB2 FOREIGN KEY (allergene_id) REFERENCES allergene (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE allergene_profil ADD CONSTRAINT FK_C1DEE16A275ED078 FOREIGN KEY (profil_id) REFERENCES profil (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE allergene_profil');
        $this->addSql('DROP TABLE scan');
    }
}
