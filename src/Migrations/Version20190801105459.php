<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190801105459 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE opportunity_file ADD opportunity_id INT NOT NULL');
        $this->addSql('ALTER TABLE opportunity_file ADD CONSTRAINT FK_70DD4F2D9A34590F FOREIGN KEY (opportunity_id) REFERENCES opportunity (id)');
        $this->addSql('CREATE INDEX IDX_70DD4F2D9A34590F ON opportunity_file (opportunity_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE opportunity_file DROP FOREIGN KEY FK_70DD4F2D9A34590F');
        $this->addSql('DROP INDEX IDX_70DD4F2D9A34590F ON opportunity_file');
        $this->addSql('ALTER TABLE opportunity_file DROP opportunity_id');
    }
}
