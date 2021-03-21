<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210321073857 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE scan ADD profil_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE scan ADD CONSTRAINT FK_C4B3B3AE275ED078 FOREIGN KEY (profil_id) REFERENCES profil (id)');
        $this->addSql('CREATE INDEX IDX_C4B3B3AE275ED078 ON scan (profil_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE scan DROP FOREIGN KEY FK_C4B3B3AE275ED078');
        $this->addSql('DROP INDEX IDX_C4B3B3AE275ED078 ON scan');
        $this->addSql('ALTER TABLE scan DROP profil_id');
    }
}
