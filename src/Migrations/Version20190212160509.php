<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190212160509 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE resource ADD doc_person VARCHAR(255) DEFAULT NULL, ADD doc_oeuvre VARCHAR(255) DEFAULT NULL, ADD doc_organisme VARCHAR(255) DEFAULT NULL, ADD doc_geo VARCHAR(255) DEFAULT NULL, ADD doc_tag VARCHAR(255) DEFAULT NULL, ADD doc_analyse TINYINT(1) DEFAULT NULL, ADD doc_rights TINYINT(1) DEFAULT NULL, ADD created_at DATETIME NOT NULL, ADD doc_oai TINYINT(1) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE resource DROP doc_person, DROP doc_oeuvre, DROP doc_organisme, DROP doc_geo, DROP doc_tag, DROP doc_analyse, DROP doc_rights, DROP created_at, DROP doc_oai');
    }
}
