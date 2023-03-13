<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230313081837 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE company_sheet ADD agreement_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE company_sheet ADD CONSTRAINT FK_C0CD338724890B2B FOREIGN KEY (agreement_id) REFERENCES agreement (id)');
        $this->addSql('CREATE INDEX IDX_C0CD338724890B2B ON company_sheet (agreement_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE company_sheet DROP FOREIGN KEY FK_C0CD338724890B2B');
        $this->addSql('DROP INDEX IDX_C0CD338724890B2B ON company_sheet');
        $this->addSql('ALTER TABLE company_sheet DROP agreement_id');
    }
}
