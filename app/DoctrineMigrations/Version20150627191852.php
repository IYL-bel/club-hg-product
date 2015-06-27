<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150627191852 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE checks ADD scores_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE checks ADD CONSTRAINT FK_9F8C0079FB0F374D FOREIGN KEY (scores_id) REFERENCES scores (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9F8C0079FB0F374D ON checks (scores_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE checks DROP FOREIGN KEY FK_9F8C0079FB0F374D');
        $this->addSql('DROP INDEX UNIQ_9F8C0079FB0F374D ON checks');
        $this->addSql('ALTER TABLE checks DROP scores_id');
    }
}
