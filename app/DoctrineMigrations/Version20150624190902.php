<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150624190902 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE contests_voting (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, contests_member_id INT DEFAULT NULL, type INT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_A69A9924A76ED395 (user_id), INDEX IDX_A69A9924DE9F1FA5 (contests_member_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE contests_voting ADD CONSTRAINT FK_A69A9924A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE contests_voting ADD CONSTRAINT FK_A69A9924DE9F1FA5 FOREIGN KEY (contests_member_id) REFERENCES contests_members (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE contests_voting');
    }
}
