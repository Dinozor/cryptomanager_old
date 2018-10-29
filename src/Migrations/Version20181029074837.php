<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181029074837 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE crypto_node CHANGE time_created time_created DATETIME NOT NULL, CHANGE time_updated time_updated DATETIME NOT NULL');
        $this->addSql('ALTER TABLE account ADD priority INT DEFAULT 10 NOT NULL, CHANGE time_created time_created DATETIME NOT NULL, CHANGE time_last_checked time_last_checked DATETIME NOT NULL, CHANGE time_updated time_updated DATETIME NOT NULL');
        $this->addSql('ALTER TABLE transaction CHANGE time_created time_created DATETIME NOT NULL, CHANGE time_updated time_updated DATETIME NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE account DROP priority, CHANGE time_created time_created DATETIME NOT NULL, CHANGE time_updated time_updated DATETIME NOT NULL, CHANGE time_last_checked time_last_checked DATETIME NOT NULL');
        $this->addSql('ALTER TABLE crypto_node CHANGE time_created time_created DATETIME NOT NULL, CHANGE time_updated time_updated DATETIME NOT NULL');
        $this->addSql('ALTER TABLE transaction CHANGE time_created time_created DATETIME NOT NULL, CHANGE time_updated time_updated DATETIME NOT NULL');
    }
}
