<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181107104214 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql("INSERT INTO currency (`name`, `code_a`, `minor_unit`, `symbol`, `is_active`, `is_locked`) VALUES
                      ('Ethereum', 'eth', 16, 'Ξ', 1, 0),
                      ('Bitcoin', 'btc', 8, '₿', 1, 0),
                      ('BitcoinCash', 'bch', 8, '₿', 1, 0),
                      ('Litecoin', 'ltc', 8, 'Ł', 1, 0),
                      ('ZCash', 'zec', 8, '', 1, 0),
                      ('Ripple', 'xrp', 8, '', 1, 0)
        ");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
    }
}
