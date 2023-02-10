<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230209091852 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE company_sheet ADD project_leader_name1 VARCHAR(255) NOT NULL, ADD project_leader_name2 VARCHAR(255) DEFAULT NULL, ADD project_leader_name3 VARCHAR(255) DEFAULT NULL, ADD project_leader_name4 VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE company_sheet DROP project_leader_name1, DROP project_leader_name2, DROP project_leader_name3, DROP project_leader_name4');
    }
}
