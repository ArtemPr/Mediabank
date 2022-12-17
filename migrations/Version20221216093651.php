<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221216093651 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE img_media_content_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE media_content_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE media_directory_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE text_media_content_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE video_media_content_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE img_media_content (id INT NOT NULL, media_content_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D37128408E856D79 ON img_media_content (media_content_id)');
        $this->addSql('CREATE TABLE media_content (id INT NOT NULL, directory_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, date_create TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delete BOOLEAN NOT NULL, uploaded_user INT NOT NULL, type INT NOT NULL, link VARCHAR(255) NOT NULL, date_update TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, date_approval TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, approval_user INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9F12B6E02C94069F ON media_content (directory_id)');
        $this->addSql('CREATE TABLE media_directory (id INT NOT NULL, pid INT NOT NULL, name VARCHAR(255) NOT NULL, order_number INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE text_media_content (id INT NOT NULL, media_content_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E33C2B118E856D79 ON text_media_content (media_content_id)');
        $this->addSql('CREATE TABLE video_media_content (id INT NOT NULL, media_content_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_94689AD08E856D79 ON video_media_content (media_content_id)');
        $this->addSql('ALTER TABLE img_media_content ADD CONSTRAINT FK_D37128408E856D79 FOREIGN KEY (media_content_id) REFERENCES media_content (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE media_content ADD CONSTRAINT FK_9F12B6E02C94069F FOREIGN KEY (directory_id) REFERENCES media_directory (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE text_media_content ADD CONSTRAINT FK_E33C2B118E856D79 FOREIGN KEY (media_content_id) REFERENCES media_content (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE video_media_content ADD CONSTRAINT FK_94689AD08E856D79 FOREIGN KEY (media_content_id) REFERENCES media_content (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE img_media_content_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE media_content_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE media_directory_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE text_media_content_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE video_media_content_id_seq CASCADE');
        $this->addSql('ALTER TABLE img_media_content DROP CONSTRAINT FK_D37128408E856D79');
        $this->addSql('ALTER TABLE media_content DROP CONSTRAINT FK_9F12B6E02C94069F');
        $this->addSql('ALTER TABLE text_media_content DROP CONSTRAINT FK_E33C2B118E856D79');
        $this->addSql('ALTER TABLE video_media_content DROP CONSTRAINT FK_94689AD08E856D79');
        $this->addSql('DROP TABLE img_media_content');
        $this->addSql('DROP TABLE media_content');
        $this->addSql('DROP TABLE media_directory');
        $this->addSql('DROP TABLE text_media_content');
        $this->addSql('DROP TABLE video_media_content');
    }
}
