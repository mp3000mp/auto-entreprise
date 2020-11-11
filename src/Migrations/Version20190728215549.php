<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190728215549 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE opportunity_status_log (id INT AUTO_INCREMENT NOT NULL, opportunity_id INT NOT NULL, user_id INT NOT NULL, date DATETIME NOT NULL, INDEX IDX_90B5B4A89A34590F (opportunity_id), INDEX IDX_90B5B4A8A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tender_status_log (id INT AUTO_INCREMENT NOT NULL, tender_id INT NOT NULL, user_id INT NOT NULL, date DATETIME NOT NULL, INDEX IDX_25461AE19245DE54 (tender_id), INDEX IDX_25461AE1A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE opportunity_status_log ADD CONSTRAINT FK_90B5B4A89A34590F FOREIGN KEY (opportunity_id) REFERENCES opportunity (id)');
        $this->addSql('ALTER TABLE opportunity_status_log ADD CONSTRAINT FK_90B5B4A8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE tender_status_log ADD CONSTRAINT FK_25461AE19245DE54 FOREIGN KEY (tender_id) REFERENCES tender (id)');
        $this->addSql('ALTER TABLE tender_status_log ADD CONSTRAINT FK_25461AE1A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE opportunity_status_log');
        $this->addSql('DROP TABLE tender_status_log');
    }
}
