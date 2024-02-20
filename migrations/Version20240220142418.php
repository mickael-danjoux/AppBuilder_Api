<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240220142418 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE notification_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE user_notification_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE notification (id INT NOT NULL, title VARCHAR(64) NOT NULL, body VARCHAR(255) DEFAULT NULL, data JSON DEFAULT \'[]\' NOT NULL, devices JSON DEFAULT \'[]\' NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN notification.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE user_notification (id INT NOT NULL, notification_id INT NOT NULL, owner_id VARCHAR(255) NOT NULL, read_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3F980AC8EF1A9D84 ON user_notification (notification_id)');
        $this->addSql('CREATE INDEX IDX_3F980AC87E3C61F9 ON user_notification (owner_id)');
        $this->addSql('COMMENT ON COLUMN user_notification.read_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE user_notification ADD CONSTRAINT FK_3F980AC8EF1A9D84 FOREIGN KEY (notification_id) REFERENCES notification (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_notification ADD CONSTRAINT FK_3F980AC87E3C61F9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" ALTER created_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE "user" ALTER created_at SET DEFAULT \'2024-01-01\'');
        $this->addSql('COMMENT ON COLUMN "user".created_at IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE notification_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE user_notification_id_seq CASCADE');
        $this->addSql('ALTER TABLE user_notification DROP CONSTRAINT FK_3F980AC8EF1A9D84');
        $this->addSql('ALTER TABLE user_notification DROP CONSTRAINT FK_3F980AC87E3C61F9');
        $this->addSql('DROP TABLE notification');
        $this->addSql('DROP TABLE user_notification');
        $this->addSql('ALTER TABLE "user" ALTER created_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE "user" ALTER created_at SET DEFAULT \'2024-01-01 00:00:00\'');
        $this->addSql('COMMENT ON COLUMN "user".created_at IS NULL');
    }
}
