<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231025212936 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE currency_rate MODIFY id INT NOT NULL');
        $this->addSql('DROP INDEX `primary` ON currency_rate');
        $this->addSql('ALTER TABLE currency_rate DROP id, CHANGE currency_id currency_id VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE currency_rate ADD PRIMARY KEY (currency_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE currency_rate ADD id INT AUTO_INCREMENT NOT NULL, CHANGE currency_id currency_id VARCHAR(255) NOT NULL, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
    }
}
