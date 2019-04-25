<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190425075436 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE category MODIFY category_id INT NOT NULL');
        $this->addSql('ALTER TABLE category DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE category CHANGE category_id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE category ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE fund DROP FOREIGN KEY FK_DC923E10A5DDDF89');
        $this->addSql('DROP INDEX fk_fund_category1_idx ON fund');
        $this->addSql('ALTER TABLE fund CHANGE category_category_id category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE fund ADD CONSTRAINT FK_DC923E1012469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('CREATE INDEX fk_fund_category1_idx ON fund (category_id)');
        $this->addSql('DROP INDEX fund_base_isin_uindex ON fund_base');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE category MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE category DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE category CHANGE id category_id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE category ADD PRIMARY KEY (category_id)');
        $this->addSql('ALTER TABLE fund DROP FOREIGN KEY FK_DC923E1012469DE2');
        $this->addSql('DROP INDEX fk_fund_category1_idx ON fund');
        $this->addSql('ALTER TABLE fund CHANGE category_id category_category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE fund ADD CONSTRAINT FK_DC923E10A5DDDF89 FOREIGN KEY (category_category_id) REFERENCES category (category_id)');
        $this->addSql('CREATE INDEX fk_fund_category1_idx ON fund (category_category_id)');
        $this->addSql('CREATE UNIQUE INDEX fund_base_isin_uindex ON fund_base (isin)');
    }
}
