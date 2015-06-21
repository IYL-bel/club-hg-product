<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150621163051 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE contests_members (id INT AUTO_INCREMENT NOT NULL, contest_id INT DEFAULT NULL, users_id INT DEFAULT NULL, description LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_62F821161CD0F0DE (contest_id), INDEX IDX_62F8211667B3B43D (users_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contests_members_photos (id INT AUTO_INCREMENT NOT NULL, contest_member_id INT DEFAULT NULL, description LONGTEXT DEFAULT NULL, file_path VARCHAR(100) DEFAULT NULL, file_name VARCHAR(100) DEFAULT NULL, INDEX IDX_D0D05C29D7C777EF (contest_member_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE contests_members ADD CONSTRAINT FK_62F821161CD0F0DE FOREIGN KEY (contest_id) REFERENCES contests (id)');
        $this->addSql('ALTER TABLE contests_members ADD CONSTRAINT FK_62F8211667B3B43D FOREIGN KEY (users_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE contests_members_photos ADD CONSTRAINT FK_D0D05C29D7C777EF FOREIGN KEY (contest_member_id) REFERENCES contests_members (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE contests_members_photos DROP FOREIGN KEY FK_D0D05C29D7C777EF');
        $this->addSql('DROP TABLE contests_members');
        $this->addSql('DROP TABLE contests_members_photos');
    }
}
