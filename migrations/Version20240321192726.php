<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240321192726 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE animal_review DROP CONSTRAINT FK_C3B2441A8E962C16');
        $this->addSql('ALTER TABLE animal_review ALTER animal_id SET NOT NULL');
        $this->addSql('ALTER TABLE animal_review ADD CONSTRAINT FK_C3B2441A8E962C16 FOREIGN KEY (animal_id) REFERENCES animal (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE animal_review DROP CONSTRAINT fk_c3b2441a8e962c16');
        $this->addSql('ALTER TABLE animal_review ALTER animal_id DROP NOT NULL');
        $this->addSql('ALTER TABLE animal_review ADD CONSTRAINT fk_c3b2441a8e962c16 FOREIGN KEY (animal_id) REFERENCES animal (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
