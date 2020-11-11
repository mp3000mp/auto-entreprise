<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190801134942 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE opportunity_status_log DROP FOREIGN KEY FK_90B5B4A8A76ED395');
        $this->addSql('DROP INDEX IDX_90B5B4A8A76ED395 ON opportunity_status_log');
        $this->addSql('ALTER TABLE opportunity_status_log CHANGE user_id created_by_id INT NOT NULL, CHANGE date created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE opportunity_status_log ADD CONSTRAINT FK_90B5B4A8B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_90B5B4A8B03A8386 ON opportunity_status_log (created_by_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE opportunity_status_log DROP FOREIGN KEY FK_90B5B4A8B03A8386');
        $this->addSql('DROP INDEX IDX_90B5B4A8B03A8386 ON opportunity_status_log');
        $this->addSql('ALTER TABLE opportunity_status_log CHANGE created_by_id user_id INT NOT NULL, CHANGE created_at date DATETIME NOT NULL');
        $this->addSql('ALTER TABLE opportunity_status_log ADD CONSTRAINT FK_90B5B4A8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_90B5B4A8A76ED395 ON opportunity_status_log (user_id)');
    }
}
