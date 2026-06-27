<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260506104500 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Rename character.building_id index to match Doctrine mapping.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE `character` RENAME INDEX idx_character_building TO IDX_937AB0344D2A7E12');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE `character` RENAME INDEX IDX_937AB0344D2A7E12 TO idx_character_building');
    }
}
