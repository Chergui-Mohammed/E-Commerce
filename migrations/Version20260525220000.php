<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260525220000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create Category and Product tables';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE category (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(100) NOT NULL, slug VARCHAR(100) NOT NULL, description CLOB DEFAULT NULL, bootstrap_color VARCHAR(50) NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_64C19C1989D9B62 ON category (slug)');
        $this->addSql('CREATE TABLE product (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, category_id INTEGER NOT NULL, name VARCHAR(150) NOT NULL, description CLOB NOT NULL, price NUMERIC(10, 2) NOT NULL, sku VARCHAR(50) NOT NULL, in_stock BOOLEAN NOT NULL, image VARCHAR(255) DEFAULT NULL, CONSTRAINT FK_D34A04AD12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_D34A04AD12469DE2 ON product (category_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE category');
    }
}
