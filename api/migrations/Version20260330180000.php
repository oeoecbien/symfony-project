<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260330180000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Remove duplicate characters by slug and add unique index on slug.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(
            'DELETE c1 FROM `character` c1
            INNER JOIN `character` c2
            ON c1.slug = c2.slug AND c1.id > c2.id'
        );
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CHARACTER_SLUG ON `character` (slug)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX UNIQ_CHARACTER_SLUG ON `character`');
    }
}
