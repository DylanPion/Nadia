<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230315090917 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE company_sheet DROP FOREIGN KEY FK_C0CD3387398056A0');
        $this->addSql('DROP INDEX UNIQ_C0CD3387398056A0 ON company_sheet');
        $this->addSql('ALTER TABLE company_sheet DROP total_amount_repaid_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE company_sheet ADD total_amount_repaid_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE company_sheet ADD CONSTRAINT FK_C0CD3387398056A0 FOREIGN KEY (total_amount_repaid_id) REFERENCES total_amount_repaid_to_date (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C0CD3387398056A0 ON company_sheet (total_amount_repaid_id)');
    }
}
