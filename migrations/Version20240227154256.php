<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240227154256 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE device ADD platform VARCHAR(16) NOT NULL');
        $this->addSql('ALTER TABLE "user" DROP deleted_at');
        $this->addSql('ALTER TABLE "user" ALTER created_at SET DEFAULT \'2024-01-01\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE "user" ADD deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ALTER created_at SET DEFAULT \'2024-01-01 00:00:00\'');
        $this->addSql('ALTER TABLE device DROP platform');
    }
}
