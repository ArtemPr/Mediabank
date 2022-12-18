<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221218090152 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE media_content ADD directory_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE media_content ADD CONSTRAINT FK_9F12B6E02C94069F FOREIGN KEY (directory_id) REFERENCES media_directory (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_9F12B6E02C94069F ON media_content (directory_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE media_content DROP CONSTRAINT FK_9F12B6E02C94069F');
        $this->addSql('DROP INDEX IDX_9F12B6E02C94069F');
        $this->addSql('ALTER TABLE media_content DROP directory_id');
    }
}
