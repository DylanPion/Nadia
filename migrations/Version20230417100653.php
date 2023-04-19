<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230417100653 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE agreement (id INT AUTO_INCREMENT NOT NULL, number INT NOT NULL, cash_fund INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE association (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE company_sheet (id INT AUTO_INCREMENT NOT NULL, association_id INT DEFAULT NULL, agreement_id INT DEFAULT NULL, company_name VARCHAR(255) NOT NULL, loan_status VARCHAR(255) NOT NULL, date_of_ce DATE NOT NULL, repayment_start_date DATE NOT NULL, repayment_end_date DATE DEFAULT NULL, fni_amount_requested INT NOT NULL, payment_one INT NOT NULL, payment_two INT DEFAULT NULL, payment_one_date DATE NOT NULL, payment_two_date DATE DEFAULT NULL, remains_to_be_received INT NOT NULL, INDEX IDX_C0CD3387EFB9C8A5 (association_id), INDEX IDX_C0CD338724890B2B (agreement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project_leader (id INT AUTO_INCREMENT NOT NULL, company_sheet_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, INDEX IDX_E247019D938513C8 (company_sheet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE total_amount_repaid_to_date (id INT AUTO_INCREMENT NOT NULL, company_sheet_id INT DEFAULT NULL, payment INT NOT NULL, date DATE NOT NULL, INDEX IDX_EA85137E938513C8 (company_sheet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE company_sheet ADD CONSTRAINT FK_C0CD3387EFB9C8A5 FOREIGN KEY (association_id) REFERENCES association (id)');
        $this->addSql('ALTER TABLE company_sheet ADD CONSTRAINT FK_C0CD338724890B2B FOREIGN KEY (agreement_id) REFERENCES agreement (id)');
        $this->addSql('ALTER TABLE project_leader ADD CONSTRAINT FK_E247019D938513C8 FOREIGN KEY (company_sheet_id) REFERENCES company_sheet (id)');
        $this->addSql('ALTER TABLE total_amount_repaid_to_date ADD CONSTRAINT FK_EA85137E938513C8 FOREIGN KEY (company_sheet_id) REFERENCES company_sheet (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE company_sheet DROP FOREIGN KEY FK_C0CD3387EFB9C8A5');
        $this->addSql('ALTER TABLE company_sheet DROP FOREIGN KEY FK_C0CD338724890B2B');
        $this->addSql('ALTER TABLE project_leader DROP FOREIGN KEY FK_E247019D938513C8');
        $this->addSql('ALTER TABLE total_amount_repaid_to_date DROP FOREIGN KEY FK_EA85137E938513C8');
        $this->addSql('DROP TABLE agreement');
        $this->addSql('DROP TABLE association');
        $this->addSql('DROP TABLE company_sheet');
        $this->addSql('DROP TABLE project_leader');
        $this->addSql('DROP TABLE total_amount_repaid_to_date');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
