<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190507081826 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE portfolio_line_hist (id BIGINT AUTO_INCREMENT NOT NULL, portfolio_line_id BIGINT DEFAULT NULL, qty DOUBLE PRECISION DEFAULT NULL, lvdate DATETIME DEFAULT NULL, lvalue DOUBLE PRECISION DEFAULT NULL, INDEX fk_portfolio_line_hist_portfolio_line1_idx (portfolio_line_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE portfolio_line_hist ADD CONSTRAINT FK_BE4F0DBA1BCE5A8C FOREIGN KEY (portfolio_line_id) REFERENCES portfolio_line (id)');
        $this->addSql('DROP TABLE pf_line_hist');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE pf_line_hist (id BIGINT AUTO_INCREMENT NOT NULL, portfolio_line_id BIGINT DEFAULT NULL, lvdate DATETIME DEFAULT NULL, qty DOUBLE PRECISION DEFAULT NULL, lvalue DOUBLE PRECISION DEFAULT NULL, INDEX fk_pf_line_histo_portfolio_line1_idx (portfolio_line_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE pf_line_hist ADD CONSTRAINT FK_90F4E1D1BCE5A8C FOREIGN KEY (portfolio_line_id) REFERENCES portfolio_line (id)');
        $this->addSql('DROP TABLE portfolio_line_hist');
    }
}
