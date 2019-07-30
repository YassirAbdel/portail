<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190703095050 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, role VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE person (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE resource (id INT AUTO_INCREMENT NOT NULL, file_name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, title LONGTEXT NOT NULL, lang VARCHAR(255) DEFAULT NULL, comment LONGTEXT DEFAULT NULL, person LONGTEXT DEFAULT NULL, oeuvre LONGTEXT DEFAULT NULL, organisme LONGTEXT DEFAULT NULL, geo VARCHAR(255) DEFAULT NULL, tag LONGTEXT DEFAULT NULL, analyse TINYINT(1) NOT NULL, rights TINYINT(1) DEFAULT NULL, created_at DATETIME NOT NULL, oai TINYINT(1) DEFAULT \'0\', auteur LONGTEXT NOT NULL, resp1 VARCHAR(255) NOT NULL, editeur VARCHAR(255) NOT NULL, editeurlieu VARCHAR(255) NOT NULL, anneedit VARCHAR(255) NOT NULL, isbn VARCHAR(255) DEFAULT NULL, pagination VARCHAR(255) DEFAULT NULL, collection VARCHAR(255) DEFAULT NULL, idcadic VARCHAR(255) DEFAULT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE resource_person (resource_id INT NOT NULL, person_id INT NOT NULL, INDEX IDX_FC203B8C89329D25 (resource_id), INDEX IDX_FC203B8C217BBB47 (person_id), PRIMARY KEY(resource_id, person_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE resource_person ADD CONSTRAINT FK_FC203B8C89329D25 FOREIGN KEY (resource_id) REFERENCES resource (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE resource_person ADD CONSTRAINT FK_FC203B8C217BBB47 FOREIGN KEY (person_id) REFERENCES person (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE resource_person DROP FOREIGN KEY FK_FC203B8C217BBB47');
        $this->addSql('ALTER TABLE resource_person DROP FOREIGN KEY FK_FC203B8C89329D25');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE person');
        $this->addSql('DROP TABLE resource');
        $this->addSql('DROP TABLE resource_person');
    }
}
