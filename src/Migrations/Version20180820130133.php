<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180820130133 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE currency (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, code_a VARCHAR(5) NOT NULL, code_n SMALLINT DEFAULT NULL, minor_unit SMALLINT DEFAULT NULL, fraction_unit VARCHAR(255) DEFAULT NULL, symbol VARCHAR(5) DEFAULT NULL, country VARCHAR(255) DEFAULT NULL, is_active TINYINT(1) DEFAULT \'1\' NOT NULL, is_locked TINYINT(1) DEFAULT \'1\' NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `key` (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, currency_id INT DEFAULT NULL, user_guid CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', private VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, time_created DATETIME DEFAULT \'2018-01-01 00:00:00\' NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', time_last_used DATETIME DEFAULT \'2018-01-01 00:00:00\' NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_8A90ABA9A76ED395 (user_id), INDEX IDX_8A90ABA938248176 (currency_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE global_user (id INT AUTO_INCREMENT NOT NULL, guid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', UNIQUE INDEX UNIQ_8CC4E6252B6FCFB2 (guid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, api_key VARCHAR(255) NOT NULL, roles JSON NOT NULL, is_active TINYINT(1) NOT NULL, is_locked TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), UNIQUE INDEX UNIQ_8D93D649C912ED9D (api_key), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `key` ADD CONSTRAINT FK_8A90ABA9A76ED395 FOREIGN KEY (user_id) REFERENCES global_user (id)');
        $this->addSql('ALTER TABLE `key` ADD CONSTRAINT FK_8A90ABA938248176 FOREIGN KEY (currency_id) REFERENCES currency (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE `key` DROP FOREIGN KEY FK_8A90ABA938248176');
        $this->addSql('ALTER TABLE `key` DROP FOREIGN KEY FK_8A90ABA9A76ED395');
        $this->addSql('DROP TABLE currency');
        $this->addSql('DROP TABLE `key`');
        $this->addSql('DROP TABLE global_user');
        $this->addSql('DROP TABLE user');
    }
}
