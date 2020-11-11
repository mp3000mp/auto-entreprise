<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190728223115 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE opportunity_status_log ADD status_id INT NOT NULL');
        $this->addSql('ALTER TABLE opportunity_status_log ADD CONSTRAINT FK_90B5B4A86BF700BD FOREIGN KEY (status_id) REFERENCES opportunity_status (id)');
        $this->addSql('CREATE INDEX IDX_90B5B4A86BF700BD ON opportunity_status_log (status_id)');
        $this->addSql('ALTER TABLE tender_status_log ADD status_id INT NOT NULL');
        $this->addSql('ALTER TABLE tender_status_log ADD CONSTRAINT FK_25461AE16BF700BD FOREIGN KEY (status_id) REFERENCES tender_status (id)');
        $this->addSql('CREATE INDEX IDX_25461AE16BF700BD ON tender_status_log (status_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE opportunity_status_log DROP FOREIGN KEY FK_90B5B4A86BF700BD');
        $this->addSql('DROP INDEX IDX_90B5B4A86BF700BD ON opportunity_status_log');
        $this->addSql('ALTER TABLE opportunity_status_log DROP status_id');
        $this->addSql('ALTER TABLE tender_status_log DROP FOREIGN KEY FK_25461AE16BF700BD');
        $this->addSql('DROP INDEX IDX_25461AE16BF700BD ON tender_status_log');
        $this->addSql('ALTER TABLE tender_status_log DROP status_id');
    }
}
