<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220802053001 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE executor_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE genre_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE track_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE executor (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE genre (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE track (id INT NOT NULL, genre_id INT DEFAULT NULL, music_album_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, year VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D6E3F8A64296D31F ON track (genre_id)');
        $this->addSql('CREATE INDEX IDX_D6E3F8A6D624F7E9 ON track (music_album_id)');
        $this->addSql('CREATE TABLE track_executor (track_id INT NOT NULL, executor_id INT NOT NULL, PRIMARY KEY(track_id, executor_id))');
        $this->addSql('CREATE INDEX IDX_94AD79955ED23C43 ON track_executor (track_id)');
        $this->addSql('CREATE INDEX IDX_94AD79958ABD09BB ON track_executor (executor_id)');
        $this->addSql('ALTER TABLE track ADD CONSTRAINT FK_D6E3F8A64296D31F FOREIGN KEY (genre_id) REFERENCES genre (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE track ADD CONSTRAINT FK_D6E3F8A6D624F7E9 FOREIGN KEY (music_album_id) REFERENCES music_album (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE track_executor ADD CONSTRAINT FK_94AD79955ED23C43 FOREIGN KEY (track_id) REFERENCES track (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE track_executor ADD CONSTRAINT FK_94AD79958ABD09BB FOREIGN KEY (executor_id) REFERENCES executor (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE music_album DROP executor');
        $this->addSql('ALTER TABLE music_album DROP year');
        $this->addSql('ALTER TABLE music_album DROP genre');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE track_executor DROP CONSTRAINT FK_94AD79958ABD09BB');
        $this->addSql('ALTER TABLE track DROP CONSTRAINT FK_D6E3F8A64296D31F');
        $this->addSql('ALTER TABLE track_executor DROP CONSTRAINT FK_94AD79955ED23C43');
        $this->addSql('DROP SEQUENCE executor_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE genre_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE track_id_seq CASCADE');
        $this->addSql('DROP TABLE executor');
        $this->addSql('DROP TABLE genre');
        $this->addSql('DROP TABLE track');
        $this->addSql('DROP TABLE track_executor');
        $this->addSql('ALTER TABLE music_album ADD executor VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE music_album ADD year VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE music_album ADD genre VARCHAR(255) NOT NULL');
    }
}
