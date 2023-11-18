<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231117225643 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Change datetime to date';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cost CHANGE date date DATE NOT NULL');
        $this->addSql('ALTER TABLE worked_time CHANGE date date DATE NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cost CHANGE date date DATETIME NOT NULL');
        $this->addSql('ALTER TABLE worked_time CHANGE date date DATETIME NOT NULL');
    }
}
