<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150610194354 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE checks (id INT AUTO_INCREMENT NOT NULL, users_id INT DEFAULT NULL, file_path VARCHAR(100) DEFAULT NULL, file_name VARCHAR(100) DEFAULT NULL, status INT NOT NULL, comment_user VARCHAR(256) DEFAULT NULL, comment_admin VARCHAR(256) DEFAULT NULL, created_at DATETIME NOT NULL, INDEX IDX_9F8C007967B3B43D (users_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE checks ADD CONSTRAINT FK_9F8C007967B3B43D FOREIGN KEY (users_id) REFERENCES users (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE checks');
    }
}
