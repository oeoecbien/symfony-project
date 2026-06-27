<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260330153000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Seed legacy hardcoded Character data into database.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("
            INSERT INTO `character` (`name`, `slug`, `kind`, `surname`, `caste`, `knowledge`, `intelligence`, `strength`, `image`, `creation`)
            SELECT 'Anardil', 'anardil', 'Dame', 'Amie du soleil', 'Magicien', 'Sciences', 180, 180, '/dames/anardil.webp', NOW()
            WHERE NOT EXISTS (
                SELECT 1 FROM `character` WHERE `slug` = 'anardil'
            )
        ");
    }

    public function down(Schema $schema): void
    {
        $this->addSql("DELETE FROM `character` WHERE `slug` = 'anardil'");
    }
}
