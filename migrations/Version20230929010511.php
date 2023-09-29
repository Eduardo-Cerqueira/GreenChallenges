<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230929010511 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE challenge (id INT NOT NULL, created_by_id INT NOT NULL, uuid UUID NOT NULL, title VARCHAR(600) NOT NULL, description VARCHAR(255) NOT NULL, status VARCHAR(3000) NOT NULL, category VARCHAR(255) NOT NULL, subcategory VARCHAR(255) NOT NULL, points INT NOT NULL, duration INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP, tips VARCHAR(3000) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D7098951B03A8386 ON challenge (created_by_id)');
        $this->addSql('COMMENT ON COLUMN challenge.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE current_challenge (id INT NOT NULL, challenge_id_id INT NOT NULL, user_uuid_id INT NOT NULL, status VARCHAR(255) NOT NULL, points INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, uuid UUID NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C2F38FD7B961745 ON current_challenge (challenge_id_id)');
        $this->addSql('CREATE INDEX IDX_C2F38FD99B8CBC0 ON current_challenge (user_uuid_id)');
        $this->addSql('COMMENT ON COLUMN current_challenge.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN current_challenge.uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, surname VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, last_connection TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, uuid UUID NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('COMMENT ON COLUMN "user".created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN "user".uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE challenge ADD CONSTRAINT FK_D7098951B03A8386 FOREIGN KEY (created_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE current_challenge ADD CONSTRAINT FK_C2F38FD7B961745 FOREIGN KEY (challenge_id_id) REFERENCES challenge (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE current_challenge ADD CONSTRAINT FK_C2F38FD99B8CBC0 FOREIGN KEY (user_uuid_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE challenge DROP CONSTRAINT FK_D7098951B03A8386');
        $this->addSql('ALTER TABLE current_challenge DROP CONSTRAINT FK_C2F38FD7B961745');
        $this->addSql('ALTER TABLE current_challenge DROP CONSTRAINT FK_C2F38FD99B8CBC0');
        $this->addSql('DROP TABLE challenge');
        $this->addSql('DROP TABLE current_challenge');
        $this->addSql('DROP TABLE "user"');
    }
}
