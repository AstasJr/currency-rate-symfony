<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231025211451 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE currency_rate ADD id INT AUTO_INCREMENT NOT NULL, CHANGE date date DATE DEFAULT NULL, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE currency_rate MODIFY id INT NOT NULL');
        $this->addSql('DROP INDEX `PRIMARY` ON currency_rate');
        $this->addSql('ALTER TABLE currency_rate DROP id, CHANGE date date DATE NOT NULL');
        $this->addSql('ALTER TABLE currency_rate ADD PRIMARY KEY (date)');
    }
}
