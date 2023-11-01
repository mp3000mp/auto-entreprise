<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231101150636 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add label to statuses tables';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE opportunity_status ADD label VARCHAR(55) NOT NULL');
        $this->addSql('ALTER TABLE tender_status ADD label VARCHAR(55) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE opportunity_status DROP label');
        $this->addSql('ALTER TABLE tender_status DROP label');
    }
}
