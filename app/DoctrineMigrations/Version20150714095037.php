<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150714095037 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE prizes_lottery (id INT AUTO_INCREMENT NOT NULL, prize_id INT DEFAULT NULL, member_winner__id INT DEFAULT NULL, created_at DATETIME NOT NULL, started_at DATETIME NOT NULL, finished_at DATETIME NOT NULL, status INT NOT NULL, INDEX IDX_1D9BBF10BBE43214 (prize_id), UNIQUE INDEX UNIQ_1D9BBF10233C7420 (member_winner__id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE prizes_lottery_members (id INT AUTO_INCREMENT NOT NULL, prize_lottery__id INT DEFAULT NULL, user_id INT DEFAULT NULL, created_at DATETIME NOT NULL, INDEX IDX_74BF88533A052687 (prize_lottery__id), INDEX IDX_74BF8853A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE prizes_lottery ADD CONSTRAINT FK_1D9BBF10BBE43214 FOREIGN KEY (prize_id) REFERENCES prizes (id)');
        $this->addSql('ALTER TABLE prizes_lottery ADD CONSTRAINT FK_1D9BBF10233C7420 FOREIGN KEY (member_winner__id) REFERENCES prizes_lottery_members (id)');
        $this->addSql('ALTER TABLE prizes_lottery_members ADD CONSTRAINT FK_74BF88533A052687 FOREIGN KEY (prize_lottery__id) REFERENCES prizes_lottery (id)');
        $this->addSql('ALTER TABLE prizes_lottery_members ADD CONSTRAINT FK_74BF8853A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE prizes_lottery_members DROP FOREIGN KEY FK_74BF88533A052687');
        $this->addSql('ALTER TABLE prizes_lottery DROP FOREIGN KEY FK_1D9BBF10233C7420');
        $this->addSql('DROP TABLE prizes_lottery');
        $this->addSql('DROP TABLE prizes_lottery_members');
    }
}
