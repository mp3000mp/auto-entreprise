<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231129200218 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tender_file (id INT AUTO_INCREMENT NOT NULL, tender_id INT NOT NULL, created_by_id INT NOT NULL, type VARCHAR(12) NOT NULL, name VARCHAR(255) NOT NULL, extension VARCHAR(500) NOT NULL, path VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_5A4ED5409245DE54 (tender_id), INDEX IDX_5A4ED540B03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tender_file ADD CONSTRAINT FK_5A4ED5409245DE54 FOREIGN KEY (tender_id) REFERENCES tender (id)');
        $this->addSql('ALTER TABLE tender_file ADD CONSTRAINT FK_5A4ED540B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE opportunity DROP bill_file_docx, DROP bill_file_pdf');
        $this->addSql('ALTER TABLE opportunity_file ADD type VARCHAR(12) NOT NULL, CHANGE title name VARCHAR(255) NOT NULL, CHANGE description extension VARCHAR(500) NOT NULL');
        $this->addSql('ALTER TABLE tender DROP tender_file_docx, DROP tender_file_pdf');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tender_file DROP FOREIGN KEY FK_5A4ED5409245DE54');
        $this->addSql('ALTER TABLE tender_file DROP FOREIGN KEY FK_5A4ED540B03A8386');
        $this->addSql('DROP TABLE tender_file');
        $this->addSql('ALTER TABLE tender ADD tender_file_docx VARCHAR(255) DEFAULT NULL, ADD tender_file_pdf VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE opportunity_file DROP type, CHANGE name title VARCHAR(255) NOT NULL, CHANGE extension description VARCHAR(500) NOT NULL');
        $this->addSql('ALTER TABLE opportunity ADD bill_file_docx VARCHAR(255) DEFAULT NULL, ADD bill_file_pdf VARCHAR(255) DEFAULT NULL');
    }
}
