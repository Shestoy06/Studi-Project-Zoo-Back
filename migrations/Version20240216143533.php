<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240216143533 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE animal_image DROP CONSTRAINT fk_e4ceddab5eb747a3');
        $this->addSql('DROP INDEX idx_e4ceddab5eb747a3');
        $this->addSql('ALTER TABLE animal_image RENAME COLUMN animal_id_id TO animal_id');
        $this->addSql('ALTER TABLE animal_image ADD CONSTRAINT FK_E4CEDDAB8E962C16 FOREIGN KEY (animal_id) REFERENCES animal (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_E4CEDDAB8E962C16 ON animal_image (animal_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE animal_image DROP CONSTRAINT FK_E4CEDDAB8E962C16');
        $this->addSql('DROP INDEX IDX_E4CEDDAB8E962C16');
        $this->addSql('ALTER TABLE animal_image RENAME COLUMN animal_id TO animal_id_id');
        $this->addSql('ALTER TABLE animal_image ADD CONSTRAINT fk_e4ceddab5eb747a3 FOREIGN KEY (animal_id_id) REFERENCES animal (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_e4ceddab5eb747a3 ON animal_image (animal_id_id)');
    }
}
