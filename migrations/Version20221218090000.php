<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221218090000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE media_content DROP CONSTRAINT fk_9f12b6e02c94069f');
        $this->addSql('DROP INDEX uniq_9f12b6e02c94069f');
        $this->addSql('ALTER TABLE media_content DROP directory_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE media_content ADD directory_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE media_content ADD CONSTRAINT fk_9f12b6e02c94069f FOREIGN KEY (directory_id) REFERENCES media_directory (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_9f12b6e02c94069f ON media_content (directory_id)');
    }
}
