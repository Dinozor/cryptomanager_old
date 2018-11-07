<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181107115645 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $currencies = $this->connection->executeQuery('SELECT id, code_a FROM currency')->fetchAll();
        $currencyList = [];
        foreach ($currencies as $currency) {
            $currencyList[$currency['code_a']] = $currency['id'];
        }

        $time = new \DateTimeImmutable();
        $date = $time->format('Y-m-d H:i:s');

        $this->addSql("INSERT INTO crypto_node (`currency_id`, `name`, `class_name`, `main_address`, `time_created`, `is_locked`, `is_enabled`, `time_updated`) VALUES
                      ({$currencyList['eth']}, 'eth', 'App\\\Service\\\Node\\\Ethereum\\\EthereumAdapter', '', '{$date}', 0, 1, '{$date}'),
                      ({$currencyList['btc']}, 'btc', 'App\\\Service\\\Node\\\Bitcoin\\\BitcoinAdapter', '', '{$date}', 0, 1, '{$date}'),
                      ({$currencyList['bch']}, 'bch', 'App\\\Service\\\Node\\\BitcoinCash\\\BitcoinCashAdapter', '', '{$date}', 0, 1, '{$date}'),
                      ({$currencyList['ltc']}, 'ltc', 'App\\\Service\\\Node\\\Litecoin\\\LitecoinAdapter', '', '{$date}', 0, 1, '{$date}'),
                      ({$currencyList['zec']}, 'zec', 'App\\\Service\\\Node\\\ZCash\\\ZCashAdapter', '', '{$date}', 0, 1, '{$date}'),
                      ({$currencyList['xrp']}, 'xrp', 'App\\\Service\\\Node\\\Ripple\\\RippleAdapter', '', '{$date}', 0, 1, '{$date}')
        ");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
    }
}
