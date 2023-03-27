<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230307095449 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE character_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE dinner_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE character (id INT NOT NULL, uuid UUID NOT NULL, name VARCHAR(255) NOT NULL, short_description VARCHAR(512) NOT NULL, description TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN character.uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE character_dinner (character_id INT NOT NULL, dinner_id INT NOT NULL, PRIMARY KEY(character_id, dinner_id))');
        $this->addSql('CREATE INDEX IDX_2AF6D3A81136BE75 ON character_dinner (character_id)');
        $this->addSql('CREATE INDEX IDX_2AF6D3A8C8B1AA0C ON character_dinner (dinner_id)');
        $this->addSql('CREATE TABLE dinner (id INT NOT NULL, name VARCHAR(127) NOT NULL, uuid UUID NOT NULL, active BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN dinner.uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE character_dinner ADD CONSTRAINT FK_2AF6D3A81136BE75 FOREIGN KEY (character_id) REFERENCES character (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE character_dinner ADD CONSTRAINT FK_2AF6D3A8C8B1AA0C FOREIGN KEY (dinner_id) REFERENCES dinner (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_937AB034D17F50A6 ON character (uuid)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_937AB0345E237E06 ON character (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_89B4103BD17F50A6 ON dinner (uuid)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE character_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE dinner_id_seq CASCADE');
        $this->addSql('ALTER TABLE character_dinner DROP CONSTRAINT FK_2AF6D3A81136BE75');
        $this->addSql('ALTER TABLE character_dinner DROP CONSTRAINT FK_2AF6D3A8C8B1AA0C');
        $this->addSql('DROP INDEX UNIQ_937AB034D17F50A6');
        $this->addSql('DROP INDEX UNIQ_937AB0345E237E06');
        $this->addSql('DROP INDEX UNIQ_89B4103BD17F50A6');
        $this->addSql('DROP TABLE character');
        $this->addSql('DROP TABLE character_dinner');
        $this->addSql('DROP TABLE dinner');
    }
}
