<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240514141203 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE book (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(50) NOT NULL, isbn VARCHAR(15) NOT NULL, author VARCHAR(50) NOT NULL, image CLOB NOT NULL)');
        $this->addSql('DROP TABLE library');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE library (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(50) NOT NULL COLLATE "BINARY", isbn VARCHAR(20) NOT NULL COLLATE "BINARY", author VARCHAR(50) NOT NULL COLLATE "BINARY", image BLOB NOT NULL)');
        $this->addSql('DROP TABLE book');
    }
}
