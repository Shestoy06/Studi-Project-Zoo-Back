<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240319153011 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE animal_food (id INT GENERATED BY DEFAULT AS IDENTITY NOT NULL, food_type VARCHAR(255) NOT NULL, food_quantity VARCHAR(255) NOT NULL, date_time DATE NOT NULL, animal_id_id INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_931568C35EB747A3 ON animal_food (animal_id_id)');
        $this->addSql('ALTER TABLE animal_food ADD CONSTRAINT FK_931568C35EB747A3 FOREIGN KEY (animal_id_id) REFERENCES animal (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE animal DROP food_type');
        $this->addSql('ALTER TABLE animal DROP food_quantity');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE animal_food DROP CONSTRAINT FK_931568C35EB747A3');
        $this->addSql('DROP TABLE animal_food');
        $this->addSql('ALTER TABLE animal ADD food_type VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE animal ADD food_quantity VARCHAR(255) NOT NULL');
    }
}
