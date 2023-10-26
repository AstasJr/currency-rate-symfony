<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231025214447 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX `primary` ON currency_rate');
        $this->addSql('ALTER TABLE currency_rate CHANGE date date DATE NOT NULL');
        $this->addSql('CREATE INDEX IDX_555B7C4D38248176 ON currency_rate (currency_id)');
        $this->addSql('ALTER TABLE currency_rate ADD PRIMARY KEY (currency_id, date)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_555B7C4D38248176 ON currency_rate');
        $this->addSql('DROP INDEX `PRIMARY` ON currency_rate');
        $this->addSql('ALTER TABLE currency_rate CHANGE date date DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE currency_rate ADD PRIMARY KEY (currency_id)');
    }
}
