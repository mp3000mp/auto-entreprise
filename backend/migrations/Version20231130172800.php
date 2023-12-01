<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231130172800 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // add opportunity_id column
        $this->addSql('ALTER TABLE worked_time ADD COLUMN opportunity_id INT DEFAULT NULL');
        $this->addSql('
            UPDATE worked_time
            INNER JOIN tender ON worked_time.tender_id = tender.id
            SET worked_time.opportunity_id = tender.opportunity_id
            WHERE 1=1
        ');
        $this->addSql('ALTER TABLE worked_time MODIFY opportunity_id INT NOT NULL');
        $this->addSql('ALTER TABLE worked_time ADD CONSTRAINT FK_71DECF929A34590F FOREIGN KEY (opportunity_id) REFERENCES opportunity (id)');
        $this->addSql('CREATE INDEX IDX_71DECF929A34590F ON worked_time (opportunity_id)');

        // remove tender_id column
        $this->addSql('ALTER TABLE worked_time DROP FOREIGN KEY FK_71DECF929245DE54');
        $this->addSql('DROP INDEX IDX_71DECF929245DE54 ON worked_time');
        $this->addSql('ALTER TABLE worked_time DROP COLUMN tender_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE worked_time DROP FOREIGN KEY FK_71DECF929A34590F');
        $this->addSql('DROP INDEX IDX_71DECF929A34590F ON worked_time');
        $this->addSql('ALTER TABLE worked_time CHANGE opportunity_id tender_id INT NOT NULL');
        $this->addSql('ALTER TABLE worked_time ADD CONSTRAINT FK_71DECF929245DE54 FOREIGN KEY (tender_id) REFERENCES tender (id)');
        $this->addSql('CREATE INDEX IDX_71DECF929245DE54 ON worked_time (tender_id)');
    }
}
