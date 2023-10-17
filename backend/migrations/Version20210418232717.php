<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231018232717 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'V1 state';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE company (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, street1 VARCHAR(100) NOT NULL, street2 VARCHAR(100) DEFAULT NULL, city VARCHAR(55) NOT NULL, postcode VARCHAR(10) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE company_audit_trail (id INT AUTO_INCREMENT NOT NULL, entity_id INT NOT NULL, user_id INT DEFAULT NULL, date DATETIME NOT NULL, reason VARCHAR(55) NOT NULL, modif_type SMALLINT NOT NULL COMMENT \'1=insert, 2=update, 3=delete\', details LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', INDEX IDX_ACF259E081257D5D (entity_id), INDEX IDX_ACF259E0A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contact (id INT AUTO_INCREMENT NOT NULL, company_id INT NOT NULL, last_name VARCHAR(55) NOT NULL, first_name VARCHAR(55) NOT NULL, email VARCHAR(55) NOT NULL, phone VARCHAR(15) DEFAULT NULL, comments LONGTEXT DEFAULT NULL, INDEX IDX_4C62E638979B1AD6 (company_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contact_audit_trail (id INT AUTO_INCREMENT NOT NULL, entity_id INT NOT NULL, user_id INT DEFAULT NULL, date DATETIME NOT NULL, reason VARCHAR(55) NOT NULL, modif_type SMALLINT NOT NULL COMMENT \'1=insert, 2=update, 3=delete\', details LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', INDEX IDX_DB8F5EC681257D5D (entity_id), INDEX IDX_DB8F5EC6A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cost (id INT AUTO_INCREMENT NOT NULL, type_id INT NOT NULL, date DATETIME NOT NULL, description LONGTEXT NOT NULL, amount DOUBLE PRECISION NOT NULL, INDEX IDX_182694FCC54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cost_type (id INT AUTO_INCREMENT NOT NULL, position SMALLINT NOT NULL, trad LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mean_of_payment (id INT AUTO_INCREMENT NOT NULL, position SMALLINT NOT NULL, trad LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mp3000mp_terms_of_service (id INT AUTO_INCREMENT NOT NULL, published_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mp3000mp_terms_of_service_signature (id INT AUTO_INCREMENT NOT NULL, terms_of_service_id INT DEFAULT NULL, user_id INT DEFAULT NULL, signed_at DATETIME NOT NULL, INDEX IDX_810727E0A5DEBC29 (terms_of_service_id), INDEX IDX_810727E0A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE opportunity (id INT AUTO_INCREMENT NOT NULL, company_id INT NOT NULL, status_id INT NOT NULL, mean_of_payment_id INT NOT NULL, ref VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, canceled_at DATETIME DEFAULT NULL, billed_at DATETIME DEFAULT NULL, payed_at DATETIME DEFAULT NULL, purchased_at DATETIME DEFAULT NULL, tracked_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, forecasted_delivery DATETIME DEFAULT NULL, customer_ref1 VARCHAR(100) DEFAULT NULL, customer_ref2 VARCHAR(100) DEFAULT NULL, payment_ref VARCHAR(100) DEFAULT NULL, comments LONGTEXT DEFAULT NULL, bill_file_docx VARCHAR(255) DEFAULT NULL, bill_file_pdf VARCHAR(255) DEFAULT NULL, INDEX IDX_8389C3D7979B1AD6 (company_id), INDEX IDX_8389C3D76BF700BD (status_id), INDEX IDX_8389C3D75F286933 (mean_of_payment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE opportunity_contact (opportunity_id INT NOT NULL, contact_id INT NOT NULL, INDEX IDX_6FE72049A34590F (opportunity_id), INDEX IDX_6FE7204E7A1254A (contact_id), PRIMARY KEY(opportunity_id, contact_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE opportunity_audit_trail (id INT AUTO_INCREMENT NOT NULL, entity_id INT NOT NULL, user_id INT DEFAULT NULL, date DATETIME NOT NULL, reason VARCHAR(55) NOT NULL, modif_type SMALLINT NOT NULL COMMENT \'1=insert, 2=update, 3=delete\', details LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', INDEX IDX_BE39DBD581257D5D (entity_id), INDEX IDX_BE39DBD5A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE opportunity_file (id INT AUTO_INCREMENT NOT NULL, opportunity_id INT NOT NULL, created_by_id INT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(500) NOT NULL, path VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_70DD4F2D9A34590F (opportunity_id), INDEX IDX_70DD4F2DB03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE opportunity_status (id INT AUTO_INCREMENT NOT NULL, trad LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', position SMALLINT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE opportunity_status_log (id INT AUTO_INCREMENT NOT NULL, opportunity_id INT NOT NULL, created_by_id INT NOT NULL, status_id INT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_90B5B4A89A34590F (opportunity_id), INDEX IDX_90B5B4A8B03A8386 (created_by_id), INDEX IDX_90B5B4A86BF700BD (status_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tender (id INT AUTO_INCREMENT NOT NULL, status_id INT NOT NULL, opportunity_id INT NOT NULL, version SMALLINT NOT NULL, average_daily_rate INT NOT NULL, created_at DATETIME NOT NULL, accepted_at DATETIME DEFAULT NULL, canceled_at DATETIME DEFAULT NULL, refused_at DATETIME DEFAULT NULL, sent_at DATETIME DEFAULT NULL, comments LONGTEXT DEFAULT NULL, tender_file_docx VARCHAR(255) DEFAULT NULL, tender_file_pdf VARCHAR(255) DEFAULT NULL, INDEX IDX_42057A776BF700BD (status_id), INDEX IDX_42057A779A34590F (opportunity_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tender_row (id INT AUTO_INCREMENT NOT NULL, tender_id INT NOT NULL, position SMALLINT NOT NULL, sold_days DOUBLE PRECISION NOT NULL, title VARCHAR(55) NOT NULL, description VARCHAR(255) NOT NULL, INDEX IDX_83704E7B9245DE54 (tender_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tender_status (id INT AUTO_INCREMENT NOT NULL, position SMALLINT NOT NULL, trad LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tender_status_log (id INT AUTO_INCREMENT NOT NULL, tender_id INT NOT NULL, created_by_id INT NOT NULL, status_id INT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_25461AE19245DE54 (tender_id), INDEX IDX_25461AE1B03A8386 (created_by_id), INDEX IDX_25461AE16BF700BD (status_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(55) NOT NULL, password VARCHAR(255) DEFAULT NULL, nb_failed_connexion SMALLINT NOT NULL, is_active TINYINT(1) NOT NULL, first_name VARCHAR(55) NOT NULL, last_name VARCHAR(55) NOT NULL, password_updated_at DATETIME DEFAULT NULL, reset_password_token VARCHAR(255) DEFAULT NULL, reset_password_at DATETIME DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', locale VARCHAR(2) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE worked_time (id INT AUTO_INCREMENT NOT NULL, tender_id INT NOT NULL, user_id INT NOT NULL, worked_days DOUBLE PRECISION NOT NULL, date DATETIME NOT NULL, INDEX IDX_71DECF929245DE54 (tender_id), INDEX IDX_71DECF92A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE company_audit_trail ADD CONSTRAINT FK_ACF259E081257D5D FOREIGN KEY (entity_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE company_audit_trail ADD CONSTRAINT FK_ACF259E0A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE contact ADD CONSTRAINT FK_4C62E638979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE contact_audit_trail ADD CONSTRAINT FK_DB8F5EC681257D5D FOREIGN KEY (entity_id) REFERENCES contact (id)');
        $this->addSql('ALTER TABLE contact_audit_trail ADD CONSTRAINT FK_DB8F5EC6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE cost ADD CONSTRAINT FK_182694FCC54C8C93 FOREIGN KEY (type_id) REFERENCES cost_type (id)');
        $this->addSql('ALTER TABLE mp3000mp_terms_of_service_signature ADD CONSTRAINT FK_810727E0A5DEBC29 FOREIGN KEY (terms_of_service_id) REFERENCES mp3000mp_terms_of_service (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mp3000mp_terms_of_service_signature ADD CONSTRAINT FK_810727E0A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE opportunity ADD CONSTRAINT FK_8389C3D7979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE opportunity ADD CONSTRAINT FK_8389C3D76BF700BD FOREIGN KEY (status_id) REFERENCES opportunity_status (id)');
        $this->addSql('ALTER TABLE opportunity ADD CONSTRAINT FK_8389C3D75F286933 FOREIGN KEY (mean_of_payment_id) REFERENCES mean_of_payment (id)');
        $this->addSql('ALTER TABLE opportunity_contact ADD CONSTRAINT FK_6FE72049A34590F FOREIGN KEY (opportunity_id) REFERENCES opportunity (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE opportunity_contact ADD CONSTRAINT FK_6FE7204E7A1254A FOREIGN KEY (contact_id) REFERENCES contact (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE opportunity_audit_trail ADD CONSTRAINT FK_BE39DBD581257D5D FOREIGN KEY (entity_id) REFERENCES opportunity (id)');
        $this->addSql('ALTER TABLE opportunity_audit_trail ADD CONSTRAINT FK_BE39DBD5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE opportunity_file ADD CONSTRAINT FK_70DD4F2D9A34590F FOREIGN KEY (opportunity_id) REFERENCES opportunity (id)');
        $this->addSql('ALTER TABLE opportunity_file ADD CONSTRAINT FK_70DD4F2DB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE opportunity_status_log ADD CONSTRAINT FK_90B5B4A89A34590F FOREIGN KEY (opportunity_id) REFERENCES opportunity (id)');
        $this->addSql('ALTER TABLE opportunity_status_log ADD CONSTRAINT FK_90B5B4A8B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE opportunity_status_log ADD CONSTRAINT FK_90B5B4A86BF700BD FOREIGN KEY (status_id) REFERENCES opportunity_status (id)');
        $this->addSql('ALTER TABLE tender ADD CONSTRAINT FK_42057A776BF700BD FOREIGN KEY (status_id) REFERENCES tender_status (id)');
        $this->addSql('ALTER TABLE tender ADD CONSTRAINT FK_42057A779A34590F FOREIGN KEY (opportunity_id) REFERENCES opportunity (id)');
        $this->addSql('ALTER TABLE tender_row ADD CONSTRAINT FK_83704E7B9245DE54 FOREIGN KEY (tender_id) REFERENCES tender (id)');
        $this->addSql('ALTER TABLE tender_status_log ADD CONSTRAINT FK_25461AE19245DE54 FOREIGN KEY (tender_id) REFERENCES tender (id)');
        $this->addSql('ALTER TABLE tender_status_log ADD CONSTRAINT FK_25461AE1B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE tender_status_log ADD CONSTRAINT FK_25461AE16BF700BD FOREIGN KEY (status_id) REFERENCES tender_status (id)');
        $this->addSql('ALTER TABLE worked_time ADD CONSTRAINT FK_71DECF929245DE54 FOREIGN KEY (tender_id) REFERENCES tender (id)');
        $this->addSql('ALTER TABLE worked_time ADD CONSTRAINT FK_71DECF92A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE company_audit_trail DROP FOREIGN KEY FK_ACF259E081257D5D');
        $this->addSql('ALTER TABLE contact DROP FOREIGN KEY FK_4C62E638979B1AD6');
        $this->addSql('ALTER TABLE opportunity DROP FOREIGN KEY FK_8389C3D7979B1AD6');
        $this->addSql('ALTER TABLE contact_audit_trail DROP FOREIGN KEY FK_DB8F5EC681257D5D');
        $this->addSql('ALTER TABLE opportunity_contact DROP FOREIGN KEY FK_6FE7204E7A1254A');
        $this->addSql('ALTER TABLE cost DROP FOREIGN KEY FK_182694FCC54C8C93');
        $this->addSql('ALTER TABLE opportunity DROP FOREIGN KEY FK_8389C3D75F286933');
        $this->addSql('ALTER TABLE mp3000mp_terms_of_service_signature DROP FOREIGN KEY FK_810727E0A5DEBC29');
        $this->addSql('ALTER TABLE opportunity_contact DROP FOREIGN KEY FK_6FE72049A34590F');
        $this->addSql('ALTER TABLE opportunity_audit_trail DROP FOREIGN KEY FK_BE39DBD581257D5D');
        $this->addSql('ALTER TABLE opportunity_file DROP FOREIGN KEY FK_70DD4F2D9A34590F');
        $this->addSql('ALTER TABLE opportunity_status_log DROP FOREIGN KEY FK_90B5B4A89A34590F');
        $this->addSql('ALTER TABLE tender DROP FOREIGN KEY FK_42057A779A34590F');
        $this->addSql('ALTER TABLE opportunity DROP FOREIGN KEY FK_8389C3D76BF700BD');
        $this->addSql('ALTER TABLE opportunity_status_log DROP FOREIGN KEY FK_90B5B4A86BF700BD');
        $this->addSql('ALTER TABLE tender_row DROP FOREIGN KEY FK_83704E7B9245DE54');
        $this->addSql('ALTER TABLE tender_status_log DROP FOREIGN KEY FK_25461AE19245DE54');
        $this->addSql('ALTER TABLE worked_time DROP FOREIGN KEY FK_71DECF929245DE54');
        $this->addSql('ALTER TABLE tender DROP FOREIGN KEY FK_42057A776BF700BD');
        $this->addSql('ALTER TABLE tender_status_log DROP FOREIGN KEY FK_25461AE16BF700BD');
        $this->addSql('ALTER TABLE company_audit_trail DROP FOREIGN KEY FK_ACF259E0A76ED395');
        $this->addSql('ALTER TABLE contact_audit_trail DROP FOREIGN KEY FK_DB8F5EC6A76ED395');
        $this->addSql('ALTER TABLE mp3000mp_terms_of_service_signature DROP FOREIGN KEY FK_810727E0A76ED395');
        $this->addSql('ALTER TABLE opportunity_audit_trail DROP FOREIGN KEY FK_BE39DBD5A76ED395');
        $this->addSql('ALTER TABLE opportunity_file DROP FOREIGN KEY FK_70DD4F2DB03A8386');
        $this->addSql('ALTER TABLE opportunity_status_log DROP FOREIGN KEY FK_90B5B4A8B03A8386');
        $this->addSql('ALTER TABLE tender_status_log DROP FOREIGN KEY FK_25461AE1B03A8386');
        $this->addSql('ALTER TABLE worked_time DROP FOREIGN KEY FK_71DECF92A76ED395');
        $this->addSql('DROP TABLE company');
        $this->addSql('DROP TABLE company_audit_trail');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE contact_audit_trail');
        $this->addSql('DROP TABLE cost');
        $this->addSql('DROP TABLE cost_type');
        $this->addSql('DROP TABLE mean_of_payment');
        $this->addSql('DROP TABLE mp3000mp_terms_of_service');
        $this->addSql('DROP TABLE mp3000mp_terms_of_service_signature');
        $this->addSql('DROP TABLE opportunity');
        $this->addSql('DROP TABLE opportunity_contact');
        $this->addSql('DROP TABLE opportunity_audit_trail');
        $this->addSql('DROP TABLE opportunity_file');
        $this->addSql('DROP TABLE opportunity_status');
        $this->addSql('DROP TABLE opportunity_status_log');
        $this->addSql('DROP TABLE tender');
        $this->addSql('DROP TABLE tender_row');
        $this->addSql('DROP TABLE tender_status');
        $this->addSql('DROP TABLE tender_status_log');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE worked_time');
    }
}
