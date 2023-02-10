<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230209091553 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE company_sheet ADD loan_status VARCHAR(255) NOT NULL, ADD date_of_ce DATE NOT NULL, ADD repayment_start_date DATE NOT NULL, ADD repayment_end_date DATE DEFAULT NULL, ADD fni_amount_requested INT NOT NULL, ADD amount_paid INT NOT NULL, ADD payment_one INT NOT NULL, ADD payment_two INT DEFAULT NULL, ADD payment_one_date DATE NOT NULL, ADD payment_two_date DATE DEFAULT NULL, ADD remains_to_be_paid INT NOT NULL, ADD total_amount_repaid_to_date INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE company_sheet DROP loan_status, DROP date_of_ce, DROP repayment_start_date, DROP repayment_end_date, DROP fni_amount_requested, DROP amount_paid, DROP payment_one, DROP payment_two, DROP payment_one_date, DROP payment_two_date, DROP remains_to_be_paid, DROP total_amount_repaid_to_date');
    }
}
