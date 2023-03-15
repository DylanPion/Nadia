<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230315091647 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE company_sheet DROP total_amount_repaid_to_date');
        $this->addSql('ALTER TABLE total_amount_repaid_to_date ADD company_sheet_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE total_amount_repaid_to_date ADD CONSTRAINT FK_EA85137E938513C8 FOREIGN KEY (company_sheet_id) REFERENCES company_sheet (id)');
        $this->addSql('CREATE INDEX IDX_EA85137E938513C8 ON total_amount_repaid_to_date (company_sheet_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE company_sheet ADD total_amount_repaid_to_date INT NOT NULL');
        $this->addSql('ALTER TABLE total_amount_repaid_to_date DROP FOREIGN KEY FK_EA85137E938513C8');
        $this->addSql('DROP INDEX IDX_EA85137E938513C8 ON total_amount_repaid_to_date');
        $this->addSql('ALTER TABLE total_amount_repaid_to_date DROP company_sheet_id');
    }
}
