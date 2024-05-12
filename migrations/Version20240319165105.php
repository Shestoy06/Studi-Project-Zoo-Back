<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240319165105 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE animal_food DROP CONSTRAINT fk_931568c35eb747a3');
        $this->addSql('DROP INDEX idx_931568c35eb747a3');
        $this->addSql('ALTER TABLE animal_food RENAME COLUMN animal_id_id TO animal_id');
        $this->addSql('ALTER TABLE animal_food ADD CONSTRAINT FK_931568C38E962C16 FOREIGN KEY (animal_id) REFERENCES animal (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_931568C38E962C16 ON animal_food (animal_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE animal_food DROP CONSTRAINT FK_931568C38E962C16');
        $this->addSql('DROP INDEX IDX_931568C38E962C16');
        $this->addSql('ALTER TABLE animal_food RENAME COLUMN animal_id TO animal_id_id');
        $this->addSql('ALTER TABLE animal_food ADD CONSTRAINT fk_931568c35eb747a3 FOREIGN KEY (animal_id_id) REFERENCES animal (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_931568c35eb747a3 ON animal_food (animal_id_id)');
    }
}
