<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200312130103 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE resource CHANGE resp1 resp1 VARCHAR(255) DEFAULT NULL, CHANGE editeur editeur VARCHAR(255) DEFAULT NULL, CHANGE editeurlieu editeurlieu VARCHAR(255) DEFAULT NULL, CHANGE anneedit anneedit VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE resource CHANGE file_name file_name VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE resp1 resp1 VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE editeur editeur VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE editeurlieu editeurlieu VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE anneedit anneedit VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci');
    }
}
