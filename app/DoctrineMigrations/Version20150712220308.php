<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150712220308 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE contests ADD member_winner__id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE contests ADD CONSTRAINT FK_A0042154233C7420 FOREIGN KEY (member_winner__id) REFERENCES contests_members (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A0042154233C7420 ON contests (member_winner__id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE contests DROP FOREIGN KEY FK_A0042154233C7420');
        $this->addSql('DROP INDEX UNIQ_A0042154233C7420 ON contests');
        $this->addSql('ALTER TABLE contests DROP member_winner__id');
    }
}
