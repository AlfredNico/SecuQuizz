<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200527233133 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE families ADD parent_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE families ADD CONSTRAINT FK_995F3FCC727ACA70 FOREIGN KEY (parent_id) REFERENCES families (id)');
        $this->addSql('CREATE INDEX IDX_995F3FCC727ACA70 ON families (parent_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE families DROP FOREIGN KEY FK_995F3FCC727ACA70');
        $this->addSql('DROP INDEX IDX_995F3FCC727ACA70 ON families');
        $this->addSql('ALTER TABLE families DROP parent_id');
    }
}
