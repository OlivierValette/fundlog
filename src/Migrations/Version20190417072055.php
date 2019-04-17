<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190417072055 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('DROP TABLE userpm');
        $this->addSql('ALTER TABLE alert CHANGE portfolio_id portfolio_id BIGINT DEFAULT NULL');
        $this->addSql('ALTER TABLE fin_info CHANGE fund_id fund_id BIGINT DEFAULT NULL, CHANGE source_id source_id BIGINT DEFAULT NULL');
        $this->addSql('ALTER TABLE fund CHANGE category_category_id category_category_id INT DEFAULT NULL, CHANGE asset_class_id asset_class_id BIGINT DEFAULT NULL');
        $this->addSql('ALTER TABLE fund_hist CHANGE fund_id fund_id BIGINT DEFAULT NULL');
        $this->addSql('ALTER TABLE pf_line_hist CHANGE portfolio_line_id portfolio_line_id BIGINT DEFAULT NULL');
        $this->addSql('ALTER TABLE portfolio ADD user_id INT DEFAULT NULL, CHANGE middleman_id middleman_id BIGINT DEFAULT NULL, CHANGE lifeinsurance_id lifeinsurance_id BIGINT DEFAULT NULL, CHANGE archived archived TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE portfolio ADD CONSTRAINT FK_A9ED1062A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX fk_portfolio_user_idx ON portfolio (user_id)');
        $this->addSql('ALTER TABLE portfolio_hist CHANGE portfolio_id portfolio_id BIGINT DEFAULT NULL');
        $this->addSql('ALTER TABLE portfolio_io CHANGE portfolio_id portfolio_id BIGINT DEFAULT NULL');
        $this->addSql('ALTER TABLE portfolio_line CHANGE portfolio_id portfolio_id BIGINT DEFAULT NULL, CHANGE fund_id fund_id BIGINT DEFAULT NULL, CHANGE io_hide io_hide TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE scraping CHANGE source_id source_id BIGINT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE portfolio DROP FOREIGN KEY FK_A9ED1062A76ED395');
        $this->addSql('CREATE TABLE userpm (id BIGINT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL COLLATE utf8_bin, password VARCHAR(255) NOT NULL COLLATE utf8_bin COMMENT \'bcrypt encoded\', roles JSON NOT NULL, firstname VARCHAR(255) NOT NULL COLLATE utf8_bin, lastname VARCHAR(255) NOT NULL COLLATE utf8_bin, created_at DATETIME NOT NULL, UNIQUE INDEX email_UNIQUE (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE user');
        $this->addSql('ALTER TABLE alert CHANGE portfolio_id portfolio_id BIGINT NOT NULL');
        $this->addSql('ALTER TABLE fin_info CHANGE fund_id fund_id BIGINT NOT NULL, CHANGE source_id source_id BIGINT NOT NULL');
        $this->addSql('ALTER TABLE fund CHANGE asset_class_id asset_class_id BIGINT NOT NULL, CHANGE category_category_id category_category_id INT NOT NULL');
        $this->addSql('ALTER TABLE fund_hist CHANGE fund_id fund_id BIGINT NOT NULL');
        $this->addSql('ALTER TABLE pf_line_hist CHANGE portfolio_line_id portfolio_line_id BIGINT NOT NULL');
        $this->addSql('DROP INDEX fk_portfolio_user_idx ON portfolio');
        $this->addSql('ALTER TABLE portfolio DROP user_id, CHANGE lifeinsurance_id lifeinsurance_id BIGINT NOT NULL, CHANGE middleman_id middleman_id BIGINT NOT NULL, CHANGE archived archived TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE portfolio_hist CHANGE portfolio_id portfolio_id BIGINT NOT NULL');
        $this->addSql('ALTER TABLE portfolio_io CHANGE portfolio_id portfolio_id BIGINT NOT NULL');
        $this->addSql('ALTER TABLE portfolio_line CHANGE fund_id fund_id BIGINT NOT NULL, CHANGE portfolio_id portfolio_id BIGINT NOT NULL, CHANGE io_hide io_hide TINYINT(1) DEFAULT \'0\'');
        $this->addSql('ALTER TABLE scraping CHANGE source_id source_id BIGINT NOT NULL');
    }
}
