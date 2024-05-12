<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240225175154 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE animal_image DROP CONSTRAINT FK_E4CEDDAB8E962C16');
        $this->addSql('ALTER TABLE animal_image ADD CONSTRAINT FK_E4CEDDAB8E962C16 FOREIGN KEY (animal_id) REFERENCES animal (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE animal_image DROP CONSTRAINT fk_e4ceddab8e962c16');
        $this->addSql('ALTER TABLE animal_image ADD CONSTRAINT fk_e4ceddab8e962c16 FOREIGN KEY (animal_id) REFERENCES animal (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
