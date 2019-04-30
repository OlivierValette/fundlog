<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190430131854 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE scraping ADD tag0 VARCHAR(255) DEFAULT NULL, ADD class0 VARCHAR(255) DEFAULT NULL, ADD attr0 VARCHAR(255) DEFAULT NULL, ADD moreover VARCHAR(255) DEFAULT NULL, DROP tag, DROP class, DROP attr, DROP `else`, CHANGE `index` index0 INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE scraping ADD tag VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, ADD class VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, ADD attr VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, ADD `else` VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, DROP tag0, DROP class0, DROP attr0, DROP moreover, CHANGE index0 `index` INT DEFAULT NULL');
    }
}
