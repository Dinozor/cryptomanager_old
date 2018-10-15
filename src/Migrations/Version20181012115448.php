<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181012115448 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE account ADD time_last_checked DATETIME NOT NULL, CHANGE time_created time_created DATETIME NOT NULL, CHANGE time_updated time_updated DATETIME NOT NULL');
        $this->addSql('ALTER TABLE currency CHANGE is_active is_enabled TINYINT(1) DEFAULT \'1\' NOT NULL');
        $this->addSql('ALTER TABLE crypto_node CHANGE settings settings JSON DEFAULT NULL, CHANGE time_created time_created DATETIME NOT NULL, CHANGE time_updated time_updated DATETIME NOT NULL');
        $this->addSql('ALTER TABLE transaction CHANGE time_created time_created DATETIME NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE account DROP time_last_checked, CHANGE time_created time_created DATETIME NOT NULL, CHANGE time_updated time_updated DATETIME NOT NULL');
        $this->addSql('ALTER TABLE crypto_node CHANGE settings settings JSON DEFAULT NULL COMMENT \'(DC2Type:json_array)\', CHANGE time_created time_created DATETIME NOT NULL, CHANGE time_updated time_updated DATETIME NOT NULL');
        $this->addSql('ALTER TABLE currency CHANGE is_enabled is_active TINYINT(1) DEFAULT \'1\' NOT NULL');
        $this->addSql('ALTER TABLE transaction CHANGE time_created time_created DATETIME NOT NULL');
    }
}
