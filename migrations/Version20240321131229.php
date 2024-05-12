<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240321131229 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE animal_food DROP CONSTRAINT FK_931568C38E962C16');
        $this->addSql('ALTER TABLE animal_food ALTER animal_id SET NOT NULL');
        $this->addSql('ALTER TABLE animal_food ADD CONSTRAINT FK_931568C38E962C16 FOREIGN KEY (animal_id) REFERENCES animal (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE animal_food DROP CONSTRAINT fk_931568c38e962c16');
        $this->addSql('ALTER TABLE animal_food ALTER animal_id DROP NOT NULL');
        $this->addSql('ALTER TABLE animal_food ADD CONSTRAINT fk_931568c38e962c16 FOREIGN KEY (animal_id) REFERENCES animal (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
