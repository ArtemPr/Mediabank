<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221218121103 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE img_media_content ADD width INT DEFAULT NULL');
        $this->addSql('ALTER TABLE img_media_content ADD height INT DEFAULT NULL');
        $this->addSql('ALTER TABLE img_media_content ADD type VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE img_media_content DROP width');
        $this->addSql('ALTER TABLE img_media_content DROP height');
        $this->addSql('ALTER TABLE img_media_content DROP type');
    }
}
