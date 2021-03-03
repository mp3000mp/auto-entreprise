<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210303232148 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE company CHANGE street2 street2 VARCHAR(100) DEFAULT NULL');
        $this->addSql('ALTER TABLE company_audit_trail CHANGE user_id user_id INT DEFAULT NULL, CHANGE modif_type modif_type SMALLINT NOT NULL COMMENT \'1=insert, 2=update, 3=delete\', CHANGE details details LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE contact CHANGE phone phone VARCHAR(15) DEFAULT NULL');
        $this->addSql('ALTER TABLE contact_audit_trail CHANGE user_id user_id INT DEFAULT NULL, CHANGE modif_type modif_type SMALLINT NOT NULL COMMENT \'1=insert, 2=update, 3=delete\', CHANGE details details LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE cost_type CHANGE trad trad LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE mean_of_payment CHANGE trad trad LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE mp3000mp_terms_of_service_signature CHANGE terms_of_service_id terms_of_service_id INT DEFAULT NULL, CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE opportunity CHANGE canceled_at canceled_at DATETIME DEFAULT NULL, CHANGE billed_at billed_at DATETIME DEFAULT NULL, CHANGE payed_at payed_at DATETIME DEFAULT NULL, CHANGE purchased_at purchased_at DATETIME DEFAULT NULL, CHANGE delivered_at delivered_at DATETIME DEFAULT NULL, CHANGE forecasted_delivery forecasted_delivery DATETIME DEFAULT NULL, CHANGE customer_ref1 customer_ref1 VARCHAR(100) DEFAULT NULL, CHANGE customer_ref2 customer_ref2 VARCHAR(100) DEFAULT NULL, CHANGE payment_ref payment_ref VARCHAR(100) DEFAULT NULL, CHANGE bill_file_docx bill_file_docx VARCHAR(255) DEFAULT NULL, CHANGE bill_file_pdf bill_file_pdf VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE opportunity_audit_trail CHANGE user_id user_id INT DEFAULT NULL, CHANGE modif_type modif_type SMALLINT NOT NULL COMMENT \'1=insert, 2=update, 3=delete\', CHANGE details details LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE opportunity_status CHANGE trad trad LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE tender CHANGE accepted_at accepted_at DATETIME DEFAULT NULL, CHANGE canceled_at canceled_at DATETIME DEFAULT NULL, CHANGE refused_at refused_at DATETIME DEFAULT NULL, CHANGE sent_at sent_at DATETIME DEFAULT NULL, CHANGE tender_file_docx tender_file_docx VARCHAR(255) DEFAULT NULL, CHANGE tender_file_pdf tender_file_pdf VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE tender_status CHANGE trad trad LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE user CHANGE password password VARCHAR(255) DEFAULT NULL, CHANGE password_updated_at password_updated_at DATETIME DEFAULT NULL, CHANGE reset_password_token reset_password_token VARCHAR(255) DEFAULT NULL, CHANGE reset_password_at reset_password_at DATETIME DEFAULT NULL, CHANGE roles roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE company CHANGE street2 street2 VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE company_audit_trail CHANGE user_id user_id INT DEFAULT NULL, CHANGE modif_type modif_type SMALLINT NOT NULL COMMENT \'1=insert, 2=update, 3=delete, 4=attach, 5=detach\', CHANGE details details LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_bin`');
        $this->addSql('ALTER TABLE contact CHANGE phone phone VARCHAR(15) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE contact_audit_trail CHANGE user_id user_id INT DEFAULT NULL, CHANGE modif_type modif_type SMALLINT NOT NULL COMMENT \'1=insert, 2=update, 3=delete, 4=attach, 5=detach\', CHANGE details details LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_bin`');
        $this->addSql('ALTER TABLE cost_type CHANGE trad trad LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`');
        $this->addSql('ALTER TABLE mean_of_payment CHANGE trad trad LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`');
        $this->addSql('ALTER TABLE mp3000mp_terms_of_service_signature CHANGE terms_of_service_id terms_of_service_id INT DEFAULT NULL, CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE opportunity CHANGE canceled_at canceled_at DATETIME DEFAULT \'NULL\', CHANGE billed_at billed_at DATETIME DEFAULT \'NULL\', CHANGE payed_at payed_at DATETIME DEFAULT \'NULL\', CHANGE purchased_at purchased_at DATETIME DEFAULT \'NULL\', CHANGE delivered_at delivered_at DATETIME DEFAULT \'NULL\', CHANGE forecasted_delivery forecasted_delivery DATETIME DEFAULT \'NULL\', CHANGE customer_ref1 customer_ref1 VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE customer_ref2 customer_ref2 VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE payment_ref payment_ref VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE bill_file_docx bill_file_docx VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE bill_file_pdf bill_file_pdf VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE opportunity_audit_trail CHANGE user_id user_id INT DEFAULT NULL, CHANGE modif_type modif_type SMALLINT NOT NULL COMMENT \'1=insert, 2=update, 3=delete, 4=attach, 5=detach\', CHANGE details details LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_bin`');
        $this->addSql('ALTER TABLE opportunity_status CHANGE trad trad LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`');
        $this->addSql('ALTER TABLE tender CHANGE accepted_at accepted_at DATETIME DEFAULT \'NULL\', CHANGE canceled_at canceled_at DATETIME DEFAULT \'NULL\', CHANGE refused_at refused_at DATETIME DEFAULT \'NULL\', CHANGE sent_at sent_at DATETIME DEFAULT \'NULL\', CHANGE tender_file_docx tender_file_docx VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE tender_file_pdf tender_file_pdf VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE tender_status CHANGE trad trad LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`');
        $this->addSql('ALTER TABLE user CHANGE password password VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE password_updated_at password_updated_at DATETIME DEFAULT \'NULL\', CHANGE reset_password_token reset_password_token VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE reset_password_at reset_password_at DATETIME DEFAULT \'NULL\', CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`');
    }
}
