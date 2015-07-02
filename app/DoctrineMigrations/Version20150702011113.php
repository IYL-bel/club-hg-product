<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150702011113 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE comments_production (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, score_id INT DEFAULT NULL, name_product VARCHAR(150) DEFAULT NULL, description LONGTEXT DEFAULT NULL, status INT NOT NULL, comment_admin VARCHAR(256) DEFAULT NULL, created_at DATETIME NOT NULL, processing_at DATETIME DEFAULT NULL, INDEX IDX_967116A3A76ED395 (user_id), UNIQUE INDEX UNIQ_967116A312EB0A51 (score_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comments_production ADD CONSTRAINT FK_967116A3A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE comments_production ADD CONSTRAINT FK_967116A312EB0A51 FOREIGN KEY (score_id) REFERENCES scores (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE comments_production');
    }
}
