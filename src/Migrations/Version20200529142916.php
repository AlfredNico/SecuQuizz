<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200529142916 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE families ADD CONSTRAINT FK_995F3FCCB3E9C81 FOREIGN KEY (niveau_id) REFERENCES niveau (id)');
        $this->addSql('CREATE INDEX IDX_995F3FCCB3E9C81 ON families (niveau_id)');
        $this->addSql('ALTER TABLE users ADD niveau_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E9B3E9C81 FOREIGN KEY (niveau_id) REFERENCES niveau (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9B3E9C81 ON users (niveau_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE families DROP FOREIGN KEY FK_995F3FCCB3E9C81');
        $this->addSql('DROP INDEX IDX_995F3FCCB3E9C81 ON families');
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E9B3E9C81');
        $this->addSql('DROP INDEX UNIQ_1483A5E9B3E9C81 ON users');
        $this->addSql('ALTER TABLE users DROP niveau_id');
    }
}
