<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150702081831 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE comments_production_photos (id INT AUTO_INCREMENT NOT NULL, comments_production_id INT DEFAULT NULL, description LONGTEXT DEFAULT NULL, file_path VARCHAR(100) DEFAULT NULL, file_name VARCHAR(100) DEFAULT NULL, INDEX IDX_E10582886E5D2E2 (comments_production_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comments_production_photos ADD CONSTRAINT FK_E10582886E5D2E2 FOREIGN KEY (comments_production_id) REFERENCES comments_production (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE comments_production_photos');
    }
}
