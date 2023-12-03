<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231203152231 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D0C64E9677153098 ON cost_type (code)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_EB0A5DA977153098 ON mean_of_payment (code)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3831187877153098 ON opportunity_status (code)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F8B85D5B77153098 ON tender_status (code)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_EB0A5DA977153098 ON mean_of_payment');
        $this->addSql('DROP INDEX UNIQ_3831187877153098 ON opportunity_status');
        $this->addSql('DROP INDEX UNIQ_D0C64E9677153098 ON cost_type');
        $this->addSql('DROP INDEX UNIQ_F8B85D5B77153098 ON tender_status');
    }
}
