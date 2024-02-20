<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240220142902 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE notification DROP devices');
        $this->addSql('ALTER TABLE "user" ALTER created_at SET DEFAULT \'2024-01-01\'');
        $this->addSql('ALTER TABLE user_notification ADD devices JSON DEFAULT \'[]\' NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE notification ADD devices JSON DEFAULT \'[]\' NOT NULL');
        $this->addSql('ALTER TABLE "user" ALTER created_at SET DEFAULT \'2024-01-01 00:00:00\'');
        $this->addSql('ALTER TABLE user_notification DROP devices');
    }
}
