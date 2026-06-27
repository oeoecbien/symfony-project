<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260506100000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Character optional home castle (building_id); Celeborn → Château Silken.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE `character` ADD building_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE `character` ADD CONSTRAINT FK_CHARACTER_BUILDING FOREIGN KEY (building_id) REFERENCES building (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_CHARACTER_BUILDING ON `character` (building_id)');
        $this->addSql('UPDATE `character` c INNER JOIN building b ON b.slug = \'chateau-silken\' SET c.building_id = b.id WHERE c.name = \'Celeborn\' OR c.slug = \'celeborn\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE `character` DROP FOREIGN KEY FK_CHARACTER_BUILDING');
        $this->addSql('DROP INDEX IDX_CHARACTER_BUILDING ON `character`');
        $this->addSql('ALTER TABLE `character` DROP building_id');
    }
}
