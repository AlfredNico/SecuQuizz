<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200601152000 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE questions DROP FOREIGN KEY FK_8ADC54D5A660B158');
        $this->addSql('DROP TABLE competences');
        $this->addSql('DROP INDEX IDX_8ADC54D5A660B158 ON questions');
        $this->addSql('ALTER TABLE questions DROP competences_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE competences (id INT AUTO_INCREMENT NOT NULL, families_id INT NOT NULL, title VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_DB2077CE5DFECCD4 (families_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE competences ADD CONSTRAINT FK_DB2077CE5DFECCD4 FOREIGN KEY (families_id) REFERENCES families (id)');
        $this->addSql('ALTER TABLE questions ADD competences_id INT NOT NULL');
        $this->addSql('ALTER TABLE questions ADD CONSTRAINT FK_8ADC54D5A660B158 FOREIGN KEY (competences_id) REFERENCES competences (id)');
        $this->addSql('CREATE INDEX IDX_8ADC54D5A660B158 ON questions (competences_id)');
    }
}
