<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231019003119 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Prepare for V2';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE mp3000mp_terms_of_service_signature DROP FOREIGN KEY FK_810727E0A76ED395');
        $this->addSql('ALTER TABLE mp3000mp_terms_of_service_signature DROP FOREIGN KEY FK_810727E0A5DEBC29');
        $this->addSql('DROP TABLE mp3000mp_terms_of_service_signature');
        $this->addSql('DROP TABLE mp3000mp_terms_of_service');
        $this->addSql('ALTER TABLE company_audit_trail CHANGE modif_type modif_type INT NOT NULL COMMENT \'1=insert, 2=update, 3=delete\', CHANGE details details JSON DEFAULT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4C62E638E7927C74 ON contact (email)');
        $this->addSql('ALTER TABLE contact_audit_trail CHANGE modif_type modif_type INT NOT NULL COMMENT \'1=insert, 2=update, 3=delete\', CHANGE details details JSON DEFAULT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE cost CHANGE description description VARCHAR(255) NOT NULL');

        $this->addSql('ALTER TABLE cost_type ADD label VARCHAR(55) DEFAULT NULL');
        $this->addSql('UPDATE cost_type SET label = REPLACE(JSON_EXTRACT(trad, \'$.fr\'), \'"\', \'\') WHERE 1=1');
        $this->addSql('ALTER TABLE cost_type MODIFY label VARCHAR(55) NOT NULL');
        $this->addSql('ALTER TABLE cost_type DROP trad, CHANGE position position INT NOT NULL');

        $this->addSql('ALTER TABLE mean_of_payment ADD label VARCHAR(55) DEFAULT NULL');
        $this->addSql('UPDATE mean_of_payment SET label = REPLACE(JSON_EXTRACT(trad, \'$.fr\'), \'"\', \'\') WHERE 1=1');
        $this->addSql('ALTER TABLE mean_of_payment MODIFY label VARCHAR(55) NOT NULL');
        $this->addSql('ALTER TABLE mean_of_payment DROP trad, CHANGE position position INT NOT NULL');
        $this->addSql('ALTER TABLE opportunity CHANGE mean_of_payment_id mean_of_payment_id INT DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8389C3D7146F3EA3 ON opportunity (ref)');

        $this->addSql('ALTER TABLE opportunity_audit_trail CHANGE modif_type modif_type INT NOT NULL COMMENT \'1=insert, 2=update, 3=delete\', CHANGE details details JSON DEFAULT NULL COMMENT \'(DC2Type:json)\'');

        $this->addSql('ALTER TABLE opportunity_status ADD label VARCHAR(55) DEFAULT NULL');
        $this->addSql('UPDATE opportunity_status SET label = REPLACE(JSON_EXTRACT(trad, \'$.fr\'), \'"\', \'\') WHERE 1=1');
        $this->addSql('ALTER TABLE opportunity_status MODIFY label VARCHAR(55) NOT NULL');
        $this->addSql('ALTER TABLE opportunity_status DROP trad, CHANGE position position INT NOT NULL');

        $this->addSql('ALTER TABLE tender CHANGE version version INT NOT NULL');
        $this->addSql('ALTER TABLE tender_row CHANGE position position INT NOT NULL, CHANGE title title VARCHAR(255) NOT NULL');

        $this->addSql('ALTER TABLE tender_status ADD label VARCHAR(55) DEFAULT NULL');
        $this->addSql('UPDATE tender_status SET label = REPLACE(JSON_EXTRACT(trad, \'$.fr\'), \'"\', \'\') WHERE 1=1');
        $this->addSql('ALTER TABLE tender_status MODIFY label VARCHAR(55) NOT NULL');
        $this->addSql('ALTER TABLE tender_status DROP trad, CHANGE position position INT NOT NULL');

        $this->addSql('ALTER TABLE user ADD username VARCHAR(55) NOT NULL, DROP nb_failed_connexion, DROP is_active, DROP first_name, DROP last_name, DROP password_updated_at, DROP reset_password_token, DROP reset_password_at, DROP locale, CHANGE roles roles JSON NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON user (username)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE mp3000mp_terms_of_service_signature (id INT AUTO_INCREMENT NOT NULL, terms_of_service_id INT DEFAULT NULL, user_id INT DEFAULT NULL, signed_at DATETIME NOT NULL, INDEX IDX_810727E0A5DEBC29 (terms_of_service_id), INDEX IDX_810727E0A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE mp3000mp_terms_of_service (id INT AUTO_INCREMENT NOT NULL, published_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE mp3000mp_terms_of_service_signature ADD CONSTRAINT FK_810727E0A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mp3000mp_terms_of_service_signature ADD CONSTRAINT FK_810727E0A5DEBC29 FOREIGN KEY (terms_of_service_id) REFERENCES mp3000mp_terms_of_service (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE company_audit_trail CHANGE modif_type modif_type SMALLINT NOT NULL COMMENT \'1=insert, 2=update, 3=delete\', CHANGE details details JSON DEFAULT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE mean_of_payment ADD trad JSON NOT NULL COMMENT \'(DC2Type:json)\', DROP label, CHANGE position position SMALLINT NOT NULL');
        $this->addSql('ALTER TABLE tender_row CHANGE position position SMALLINT NOT NULL, CHANGE title title VARCHAR(55) NOT NULL');
        $this->addSql('ALTER TABLE cost CHANGE description description LONGTEXT NOT NULL');
        $this->addSql('DROP INDEX UNIQ_4C62E638E7927C74 ON contact');
        $this->addSql('ALTER TABLE tender CHANGE version version SMALLINT NOT NULL');
        $this->addSql('ALTER TABLE opportunity_status DROP label');
        $this->addSql('ALTER TABLE opportunity_status ADD trad JSON NOT NULL COMMENT \'(DC2Type:json)\', CHANGE position position SMALLINT NOT NULL');
        $this->addSql('DROP INDEX UNIQ_8D93D649F85E0677 ON user');
        $this->addSql('ALTER TABLE user ADD nb_failed_connexion SMALLINT NOT NULL, ADD is_active TINYINT(1) NOT NULL, ADD last_name VARCHAR(55) NOT NULL, ADD password_updated_at DATETIME DEFAULT NULL, ADD reset_password_token VARCHAR(255) DEFAULT NULL, ADD reset_password_at DATETIME DEFAULT NULL, ADD locale VARCHAR(2) NOT NULL, CHANGE roles roles JSON NOT NULL COMMENT \'(DC2Type:json)\', CHANGE username first_name VARCHAR(55) NOT NULL');
        $this->addSql('DROP INDEX UNIQ_8389C3D7146F3EA3 ON opportunity');
        $this->addSql('ALTER TABLE opportunity CHANGE mean_of_payment_id mean_of_payment_id INT NOT NULL');
        $this->addSql('ALTER TABLE cost_type ADD trad JSON NOT NULL COMMENT \'(DC2Type:json)\', DROP label, CHANGE position position SMALLINT NOT NULL');
        $this->addSql('ALTER TABLE tender_status DROP label');
        $this->addSql('ALTER TABLE tender_status ADD trad JSON NOT NULL COMMENT \'(DC2Type:json)\', CHANGE position position SMALLINT NOT NULL');
        $this->addSql('ALTER TABLE opportunity_audit_trail CHANGE modif_type modif_type SMALLINT NOT NULL COMMENT \'1=insert, 2=update, 3=delete\', CHANGE details details JSON DEFAULT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE contact_audit_trail CHANGE modif_type modif_type SMALLINT NOT NULL COMMENT \'1=insert, 2=update, 3=delete\', CHANGE details details JSON DEFAULT NULL COMMENT \'(DC2Type:json)\'');
    }
}
