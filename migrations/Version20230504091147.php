<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230504091147 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE weather_table ADD accounting_provision4 INT DEFAULT NULL, ADD accounting_provision3 INT DEFAULT NULL, ADD accounting_provision2 INT DEFAULT NULL, ADD accounting_provision1 INT DEFAULT NULL, ADD accounting_provision0 INT DEFAULT NULL, CHANGE retainer_percentage accounting_provision5 INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE weather_table ADD retainer_percentage INT DEFAULT NULL, DROP accounting_provision5, DROP accounting_provision4, DROP accounting_provision3, DROP accounting_provision2, DROP accounting_provision1, DROP accounting_provision0');
    }
}
