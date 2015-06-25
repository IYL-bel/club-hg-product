<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150625161111 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE prizes ADD scores_buy__id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE prizes ADD CONSTRAINT FK_F73CF5A6C388D01A FOREIGN KEY (scores_buy__id) REFERENCES scores (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F73CF5A6C388D01A ON prizes (scores_buy__id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE prizes DROP FOREIGN KEY FK_F73CF5A6C388D01A');
        $this->addSql('DROP INDEX UNIQ_F73CF5A6C388D01A ON prizes');
        $this->addSql('ALTER TABLE prizes DROP scores_buy__id');
    }
}
