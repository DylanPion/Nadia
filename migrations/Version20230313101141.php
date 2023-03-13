<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230313101141 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project_leader ADD company_sheet_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE project_leader ADD CONSTRAINT FK_E247019D938513C8 FOREIGN KEY (company_sheet_id) REFERENCES company_sheet (id)');
        $this->addSql('CREATE INDEX IDX_E247019D938513C8 ON project_leader (company_sheet_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project_leader DROP FOREIGN KEY FK_E247019D938513C8');
        $this->addSql('DROP INDEX IDX_E247019D938513C8 ON project_leader');
        $this->addSql('ALTER TABLE project_leader DROP company_sheet_id');
    }
}
