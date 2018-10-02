<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180827131849 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE account (id INT AUTO_INCREMENT NOT NULL, global_user_id INT DEFAULT NULL, currency_id INT NOT NULL, name VARCHAR(255) DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, address VARCHAR(255) NOT NULL, last_block INT NOT NULL, last_balance BIGINT NOT NULL, time_created DATETIME NOT NULL, time_updated DATETIME NOT NULL, block_when_created INT NOT NULL, password VARCHAR(255) DEFAULT NULL, INDEX IDX_7D3656A4CCEA1374 (global_user_id), INDEX IDX_7D3656A438248176 (currency_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE crypto_node (id INT AUTO_INCREMENT NOT NULL, currency_id INT NOT NULL, name VARCHAR(255) NOT NULL, class_name VARCHAR(255) NOT NULL, main_address VARCHAR(255) NOT NULL, settings JSON DEFAULT NULL COMMENT \'(DC2Type:json_array)\', time_created DATETIME NOT NULL, is_locked TINYINT(1) NOT NULL, is_enabled TINYINT(1) NOT NULL, time_updated DATETIME NOT NULL, INDEX IDX_5FA0D69A38248176 (currency_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transaction (id INT AUTO_INCREMENT NOT NULL, hash VARCHAR(255) NOT NULL, from_address VARCHAR(255) NOT NULL, to_address VARCHAR(255) NOT NULL, block VARCHAR(255) NOT NULL, time_created DATETIME NOT NULL, amount BIGINT NOT NULL, status VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE account ADD CONSTRAINT FK_7D3656A4CCEA1374 FOREIGN KEY (global_user_id) REFERENCES global_user (id)');
        $this->addSql('ALTER TABLE account ADD CONSTRAINT FK_7D3656A438248176 FOREIGN KEY (currency_id) REFERENCES currency (id)');
        $this->addSql('ALTER TABLE crypto_node ADD CONSTRAINT FK_5FA0D69A38248176 FOREIGN KEY (currency_id) REFERENCES currency (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE account');
        $this->addSql('DROP TABLE crypto_node');
        $this->addSql('DROP TABLE transaction');
    }
}
