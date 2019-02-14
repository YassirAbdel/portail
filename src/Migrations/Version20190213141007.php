<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190213141007 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE resource ADD type VARCHAR(255) NOT NULL, ADD title VARCHAR(255) NOT NULL, ADD lang VARCHAR(255) DEFAULT NULL, ADD person VARCHAR(255) DEFAULT NULL, ADD oeuvre VARCHAR(255) DEFAULT NULL, ADD organisme VARCHAR(255) DEFAULT NULL, ADD geo VARCHAR(255) DEFAULT NULL, ADD tag VARCHAR(255) DEFAULT NULL, ADD analyse TINYINT(1) DEFAULT NULL, ADD rights TINYINT(1) DEFAULT NULL, DROP doc_lang, DROP doc_person, DROP doc_oeuvre, DROP doc_organisme, DROP doc_geo, DROP doc_tag, DROP doc_analyse, DROP doc_rights, DROP doc_type, DROP doc_title, CHANGE doc_comment comment LONGTEXT DEFAULT NULL, CHANGE doc_oai oai TINYINT(1) DEFAULT \'0\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE resource ADD doc_lang VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, ADD doc_person VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, ADD doc_oeuvre VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, ADD doc_organisme VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, ADD doc_geo VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, ADD doc_tag VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, ADD doc_analyse TINYINT(1) DEFAULT NULL, ADD doc_rights TINYINT(1) DEFAULT NULL, ADD doc_type VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, ADD doc_title VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, DROP type, DROP title, DROP lang, DROP person, DROP oeuvre, DROP organisme, DROP geo, DROP tag, DROP analyse, DROP rights, CHANGE comment doc_comment LONGTEXT DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE oai doc_oai TINYINT(1) DEFAULT \'0\'');
    }
}
