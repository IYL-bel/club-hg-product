<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150617215448 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE scores (id INT AUTO_INCREMENT NOT NULL, points INT DEFAULT NULL, type INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE contests ADD scores_participation__id INT DEFAULT NULL, ADD scores_winner__id INT DEFAULT NULL, DROP points_participation, DROP points_winner');
        $this->addSql('ALTER TABLE contests ADD CONSTRAINT FK_A0042154CF6DDBBD FOREIGN KEY (scores_participation__id) REFERENCES scores (id)');
        $this->addSql('ALTER TABLE contests ADD CONSTRAINT FK_A00421547349BDBD FOREIGN KEY (scores_winner__id) REFERENCES scores (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A0042154CF6DDBBD ON contests (scores_participation__id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A00421547349BDBD ON contests (scores_winner__id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE contests DROP FOREIGN KEY FK_A0042154CF6DDBBD');
        $this->addSql('ALTER TABLE contests DROP FOREIGN KEY FK_A00421547349BDBD');
        $this->addSql('DROP TABLE scores');
        $this->addSql('DROP INDEX UNIQ_A0042154CF6DDBBD ON contests');
        $this->addSql('DROP INDEX UNIQ_A00421547349BDBD ON contests');
        $this->addSql('ALTER TABLE contests ADD points_participation INT DEFAULT NULL, ADD points_winner INT DEFAULT NULL, DROP scores_participation__id, DROP scores_winner__id');
    }
}
