<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240221185253 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE animal_habitat (id INT GENERATED BY DEFAULT AS IDENTITY NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE animal ADD animal_habitat_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE animal DROP health_state');
        $this->addSql('ALTER TABLE animal ADD CONSTRAINT FK_6AAB231F720BF5C2 FOREIGN KEY (animal_habitat_id) REFERENCES animal_habitat (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_6AAB231F720BF5C2 ON animal (animal_habitat_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE animal_habitat');
        $this->addSql('ALTER TABLE animal DROP CONSTRAINT FK_6AAB231F720BF5C2');
        $this->addSql('DROP INDEX IDX_6AAB231F720BF5C2');
        $this->addSql('ALTER TABLE animal ADD health_state VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE animal DROP animal_habitat_id');
    }
}