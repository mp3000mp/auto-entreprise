<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231114224308 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Remove useless audit trail feature';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE company_audit_trail DROP FOREIGN KEY FK_ACF259E0A76ED395');
        $this->addSql('ALTER TABLE company_audit_trail DROP FOREIGN KEY FK_ACF259E081257D5D');
        $this->addSql('ALTER TABLE opportunity_audit_trail DROP FOREIGN KEY FK_BE39DBD5A76ED395');
        $this->addSql('ALTER TABLE opportunity_audit_trail DROP FOREIGN KEY FK_BE39DBD581257D5D');
        $this->addSql('ALTER TABLE contact_audit_trail DROP FOREIGN KEY FK_DB8F5EC681257D5D');
        $this->addSql('ALTER TABLE contact_audit_trail DROP FOREIGN KEY FK_DB8F5EC6A76ED395');
        $this->addSql('DROP TABLE company_audit_trail');
        $this->addSql('DROP TABLE opportunity_audit_trail');
        $this->addSql('DROP TABLE contact_audit_trail');
        $this->addSql('ALTER TABLE opportunity_status_log CHANGE created_by_id created_by_id INT DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_42057A77BF1CD3C39A34590F ON tender (version, opportunity_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_83704E7B462CE4F59245DE54 ON tender_row (position, tender_id)');
        $this->addSql('ALTER TABLE tender_status_log CHANGE created_by_id created_by_id INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE company_audit_trail (id INT AUTO_INCREMENT NOT NULL, entity_id INT NOT NULL, user_id INT DEFAULT NULL, date DATETIME NOT NULL, reason VARCHAR(55) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, modif_type INT NOT NULL COMMENT \'1=insert, 2=update, 3=delete\', details JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', INDEX IDX_ACF259E081257D5D (entity_id), INDEX IDX_ACF259E0A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE opportunity_audit_trail (id INT AUTO_INCREMENT NOT NULL, entity_id INT NOT NULL, user_id INT DEFAULT NULL, date DATETIME NOT NULL, reason VARCHAR(55) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, modif_type INT NOT NULL COMMENT \'1=insert, 2=update, 3=delete\', details JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', INDEX IDX_BE39DBD5A76ED395 (user_id), INDEX IDX_BE39DBD581257D5D (entity_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE contact_audit_trail (id INT AUTO_INCREMENT NOT NULL, entity_id INT NOT NULL, user_id INT DEFAULT NULL, date DATETIME NOT NULL, reason VARCHAR(55) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, modif_type INT NOT NULL COMMENT \'1=insert, 2=update, 3=delete\', details JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', INDEX IDX_DB8F5EC681257D5D (entity_id), INDEX IDX_DB8F5EC6A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE company_audit_trail ADD CONSTRAINT FK_ACF259E0A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE company_audit_trail ADD CONSTRAINT FK_ACF259E081257D5D FOREIGN KEY (entity_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE opportunity_audit_trail ADD CONSTRAINT FK_BE39DBD5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE opportunity_audit_trail ADD CONSTRAINT FK_BE39DBD581257D5D FOREIGN KEY (entity_id) REFERENCES opportunity (id)');
        $this->addSql('ALTER TABLE contact_audit_trail ADD CONSTRAINT FK_DB8F5EC681257D5D FOREIGN KEY (entity_id) REFERENCES contact (id)');
        $this->addSql('ALTER TABLE contact_audit_trail ADD CONSTRAINT FK_DB8F5EC6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('DROP INDEX UNIQ_83704E7B462CE4F59245DE54 ON tender_row');
        $this->addSql('DROP INDEX UNIQ_42057A77BF1CD3C39A34590F ON tender');
        $this->addSql('ALTER TABLE opportunity_status_log CHANGE created_by_id created_by_id INT NOT NULL');
        $this->addSql('ALTER TABLE tender_status_log CHANGE created_by_id created_by_id INT NOT NULL');
    }
}
