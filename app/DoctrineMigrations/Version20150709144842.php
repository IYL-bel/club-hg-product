<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150709144842 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tests_production ADD score_id INT DEFAULT NULL, ADD comment_production__id INT DEFAULT NULL, ADD processing_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE tests_production ADD CONSTRAINT FK_F2E36C5F12EB0A51 FOREIGN KEY (score_id) REFERENCES scores (id)');
        $this->addSql('ALTER TABLE tests_production ADD CONSTRAINT FK_F2E36C5FC58016D3 FOREIGN KEY (comment_production__id) REFERENCES comments_production (id)');
        $this->addSql('CREATE INDEX IDX_F2E36C5F12EB0A51 ON tests_production (score_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F2E36C5FC58016D3 ON tests_production (comment_production__id)');
        $this->addSql('ALTER TABLE comments_production ADD after_testing TINYINT(1) DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE comments_production DROP after_testing');
        $this->addSql('ALTER TABLE tests_production DROP FOREIGN KEY FK_F2E36C5F12EB0A51');
        $this->addSql('ALTER TABLE tests_production DROP FOREIGN KEY FK_F2E36C5FC58016D3');
        $this->addSql('DROP INDEX IDX_F2E36C5F12EB0A51 ON tests_production');
        $this->addSql('DROP INDEX UNIQ_F2E36C5FC58016D3 ON tests_production');
        $this->addSql('ALTER TABLE tests_production DROP score_id, DROP comment_production__id, DROP processing_at');
    }
}
