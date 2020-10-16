<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190811141102 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE cost (id INT AUTO_INCREMENT NOT NULL, type_id INT NOT NULL, date DATETIME NOT NULL, description LONGTEXT NOT NULL, amount DOUBLE PRECISION NOT NULL, INDEX IDX_182694FCC54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cost_type (id INT AUTO_INCREMENT NOT NULL, trad JSON NOT NULL COMMENT \'(DC2Type:json_array)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cost ADD CONSTRAINT FK_182694FCC54C8C93 FOREIGN KEY (type_id) REFERENCES cost_type (id)');
        $this->addSql('ALTER TABLE opportunity_status DROP label');
        $this->addSql('ALTER TABLE tender_status DROP label');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE cost DROP FOREIGN KEY FK_182694FCC54C8C93');
        $this->addSql('DROP TABLE cost');
        $this->addSql('DROP TABLE cost_type');
        $this->addSql('ALTER TABLE opportunity_status ADD label VARCHAR(55) NOT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE tender_status ADD label VARCHAR(55) NOT NULL COLLATE utf8mb4_unicode_ci');
    }
}
