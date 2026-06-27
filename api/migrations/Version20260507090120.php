<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260507090120 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('DROP INDEX UNIQ_BUILDING_IDENTIFIER ON building');
        $this->addSql('DROP INDEX UNIQ_BUILDING_SLUG ON building');
        $this->addSql('ALTER TABLE building CHANGE identifier gls_identifier VARCHAR(40) NOT NULL, CHANGE name gls_name VARCHAR(100) NOT NULL, CHANGE slug gls_slug VARCHAR(40) NOT NULL, CHANGE caste gls_caste VARCHAR(40) NOT NULL, CHANGE strength gls_strength SMALLINT NOT NULL, CHANGE price gls_price SMALLINT DEFAULT NULL, CHANGE image gls_image VARCHAR(120) DEFAULT NULL, CHANGE creation gls_creation DATETIME NOT NULL, CHANGE modification gls_modification DATETIME NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BUILDING_IDENTIFIER ON building (gls_identifier)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BUILDING_SLUG ON building (gls_slug)');
        $this->addSql('ALTER TABLE `character` DROP FOREIGN KEY `FK_CHARACTER_BUILDING`');
        $this->addSql('DROP INDEX IDX_937AB0344D2A7E12 ON `character`');
        $this->addSql('DROP INDEX UNIQ_CHARACTER_IDENTIFIER ON `character`');
        $this->addSql('DROP INDEX UNIQ_CHARACTER_SLUG ON `character`');
        $this->addSql('ALTER TABLE `character` CHANGE name gls_name VARCHAR(20) NOT NULL, CHANGE identifier gls_identifier VARCHAR(40) NOT NULL, CHANGE slug gls_slug VARCHAR(20) NOT NULL, CHANGE kind gls_kind VARCHAR(20) NOT NULL, CHANGE surname gls_surname VARCHAR(50) NOT NULL, CHANGE caste gls_caste VARCHAR(20) DEFAULT NULL, CHANGE knowledge gls_knowledge VARCHAR(20) DEFAULT NULL, CHANGE intelligence gls_intelligence SMALLINT DEFAULT NULL, CHANGE strength gls_strength SMALLINT DEFAULT NULL, CHANGE image gls_image VARCHAR(50) DEFAULT NULL, CHANGE creation gls_creation DATETIME NOT NULL, CHANGE modification gls_modification DATETIME NOT NULL, CHANGE building_id gls_building_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE `character` ADD CONSTRAINT FK_937AB034C7B84DD3 FOREIGN KEY (gls_building_id) REFERENCES building (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_937AB034C7B84DD3 ON `character` (gls_building_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CHARACTER_IDENTIFIER ON `character` (gls_identifier)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CHARACTER_SLUG ON `character` (gls_slug)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX UNIQ_BUILDING_IDENTIFIER ON building');
        $this->addSql('DROP INDEX UNIQ_BUILDING_SLUG ON building');
        $this->addSql('ALTER TABLE building CHANGE gls_identifier identifier VARCHAR(40) NOT NULL, CHANGE gls_name name VARCHAR(100) NOT NULL, CHANGE gls_slug slug VARCHAR(40) NOT NULL, CHANGE gls_caste caste VARCHAR(40) NOT NULL, CHANGE gls_strength strength SMALLINT NOT NULL, CHANGE gls_price price SMALLINT DEFAULT NULL, CHANGE gls_image image VARCHAR(120) DEFAULT NULL, CHANGE gls_creation creation DATETIME NOT NULL, CHANGE gls_modification modification DATETIME NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BUILDING_IDENTIFIER ON building (identifier)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BUILDING_SLUG ON building (slug)');
        $this->addSql('ALTER TABLE `character` DROP FOREIGN KEY FK_937AB034C7B84DD3');
        $this->addSql('DROP INDEX IDX_937AB034C7B84DD3 ON `character`');
        $this->addSql('DROP INDEX UNIQ_CHARACTER_IDENTIFIER ON `character`');
        $this->addSql('DROP INDEX UNIQ_CHARACTER_SLUG ON `character`');
        $this->addSql('ALTER TABLE `character` CHANGE gls_name name VARCHAR(20) NOT NULL, CHANGE gls_identifier identifier VARCHAR(40) NOT NULL, CHANGE gls_slug slug VARCHAR(20) NOT NULL, CHANGE gls_kind kind VARCHAR(20) NOT NULL, CHANGE gls_surname surname VARCHAR(50) NOT NULL, CHANGE gls_caste caste VARCHAR(20) DEFAULT NULL, CHANGE gls_knowledge knowledge VARCHAR(20) DEFAULT NULL, CHANGE gls_intelligence intelligence SMALLINT DEFAULT NULL, CHANGE gls_strength strength SMALLINT DEFAULT NULL, CHANGE gls_image image VARCHAR(50) DEFAULT NULL, CHANGE gls_creation creation DATETIME NOT NULL, CHANGE gls_modification modification DATETIME NOT NULL, CHANGE gls_building_id building_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE `character` ADD CONSTRAINT `FK_CHARACTER_BUILDING` FOREIGN KEY (building_id) REFERENCES building (id) ON UPDATE NO ACTION ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_937AB0344D2A7E12 ON `character` (building_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CHARACTER_IDENTIFIER ON `character` (identifier)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CHARACTER_SLUG ON `character` (slug)');
    }
}
