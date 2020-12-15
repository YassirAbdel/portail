<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201022103254 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE basket_resource DROP FOREIGN KEY FK_D83EFBC61BE1FB52');
        $this->addSql('ALTER TABLE basket_resource DROP FOREIGN KEY FK_D83EFBC689329D25');
        $this->addSql('ALTER TABLE basket_resource ADD CONSTRAINT FK_D83EFBC61BE1FB52 FOREIGN KEY (basket_id) REFERENCES basket (id)');
        $this->addSql('ALTER TABLE basket_resource ADD CONSTRAINT FK_D83EFBC689329D25 FOREIGN KEY (resource_id) REFERENCES resource (id)');
        //$this->addSql('ALTER TABLE resource CHANGE file_name file_name VARCHAR(255) NOT NULL, CHANGE auteur auteur LONGTEXT NOT NULL');
       // $this->addSql('ALTER TABLE subject CHANGE file_name file_name VARCHAR(255) NOT NULL, CHANGE updated_at updated_at DATETIME NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE basket_resource DROP FOREIGN KEY FK_D83EFBC61BE1FB52');
        $this->addSql('ALTER TABLE basket_resource DROP FOREIGN KEY FK_D83EFBC689329D25');
        $this->addSql('ALTER TABLE basket_resource ADD CONSTRAINT FK_D83EFBC61BE1FB52 FOREIGN KEY (basket_id) REFERENCES basket (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE basket_resource ADD CONSTRAINT FK_D83EFBC689329D25 FOREIGN KEY (resource_id) REFERENCES resource (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE resource CHANGE file_name file_name VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE auteur auteur LONGTEXT DEFAULT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE subject CHANGE file_name file_name VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE updated_at updated_at DATETIME DEFAULT \'0000-01-01 00:00:00\' NOT NULL');
    }
}
