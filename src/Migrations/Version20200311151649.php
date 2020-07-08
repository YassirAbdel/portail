<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200311151649 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE resource ADD auteur_s LONGTEXT DEFAULT NULL, ADD auteur_m VARCHAR(255) DEFAULT NULL, ADD annee_s INT DEFAULT NULL, ADD pe_histo VARCHAR(255) DEFAULT NULL, ADD orig_doc VARCHAR(255) DEFAULT NULL, ADD copy_r VARCHAR(255) DEFAULT NULL, ADD rights_a VARCHAR(255) DEFAULT NULL, ADD support VARCHAR(255) DEFAULT NULL, ADD couleur VARCHAR(255) DEFAULT NULL, ADD format VARCHAR(255) DEFAULT NULL, ADD form_file VARCHAR(255) DEFAULT NULL, ADD duree VARCHAR(255) DEFAULT NULL, ADD nb_files VARCHAR(255) DEFAULT NULL, ADD cote VARCHAR(255) DEFAULT NULL, ADD sup_num VARCHAR(255) DEFAULT NULL, ADD loca_supnum VARCHAR(255) DEFAULT NULL, ADD cote_num VARCHAR(255) DEFAULT NULL, ADD loca_sup VARCHAR(255) DEFAULT NULL, ADD img VARCHAR(255) DEFAULT NULL, ADD pdf VARCHAR(255) DEFAULT NULL, ADD audio VARCHAR(255) DEFAULT NULL, ADD video VARCHAR(255) DEFAULT NULL, ADD url_doc VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE resource DROP auteur_s, DROP auteur_m, DROP annee_s, DROP pe_histo, DROP orig_doc, DROP copy_r, DROP rights_a, DROP support, DROP couleur, DROP format, DROP form_file, DROP duree, DROP nb_files, DROP cote, DROP sup_num, DROP loca_supnum, DROP cote_num, DROP loca_sup, DROP img, DROP pdf, DROP audio, DROP video, DROP url_doc, CHANGE file_name file_name VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
    }
}
