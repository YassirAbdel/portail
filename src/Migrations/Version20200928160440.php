<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200928160440 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE resource_work (resource_id INT NOT NULL, work_id INT NOT NULL, INDEX IDX_5C6EC83A89329D25 (resource_id), INDEX IDX_5C6EC83ABB3453DB (work_id), PRIMARY KEY(resource_id, work_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE resource_work ADD CONSTRAINT FK_5C6EC83A89329D25 FOREIGN KEY (resource_id) REFERENCES resource (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE resource_work ADD CONSTRAINT FK_5C6EC83ABB3453DB FOREIGN KEY (work_id) REFERENCES work (id) ON DELETE CASCADE');
        //$this->addSql('ALTER TABLE resource CHANGE file_name file_name VARCHAR(255) NOT NULL, CHANGE auteur auteur LONGTEXT NOT NULL');
        //$this->addSql('ALTER TABLE subject CHANGE file_name file_name VARCHAR(255) NOT NULL, CHANGE updated_at updated_at DATETIME NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE resource_work');
        $this->addSql('ALTER TABLE resource CHANGE file_name file_name VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE auteur auteur LONGTEXT DEFAULT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE subject CHANGE file_name file_name VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE updated_at updated_at DATETIME DEFAULT \'0000-01-01 00:00:00\' NOT NULL');
    }
}
