<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230215170706 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('create extension IF NOT EXISTS "uuid-ossp" schema pg_catalog version "1.1"');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE "user" (
                id INT NOT NULL, uuid VARCHAR(180) NOT NULL, 
                roles JSON NOT NULL, password VARCHAR(255) NOT NULL, 
                username VARCHAR(255) NOT NULL,
                is_guest BOOLEAN NOT NULL,
                
                PRIMARY KEY(id))
        ');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649D17F50A6 ON "user" (uuid)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON "user" (username)');
        $this->addSql('ALTER TABLE "user" ALTER uuid TYPE UUID USING (uuid_generate_v4())');
        $this->addSql('COMMENT ON COLUMN "user".uuid IS \'(DC2Type:uuid)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('DROP TABLE "user"');
    }
}
