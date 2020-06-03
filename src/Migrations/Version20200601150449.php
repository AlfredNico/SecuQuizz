<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200601150449 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE questions_types');
        $this->addSql('ALTER TABLE questions ADD type_id INT NOT NULL');
        $this->addSql('ALTER TABLE questions ADD CONSTRAINT FK_8ADC54D5C54C8C93 FOREIGN KEY (type_id) REFERENCES types (id)');
        $this->addSql('CREATE INDEX IDX_8ADC54D5C54C8C93 ON questions (type_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE questions_types (questions_id INT NOT NULL, types_id INT NOT NULL, INDEX IDX_DC48194C8EB23357 (types_id), INDEX IDX_DC48194CBCB134CE (questions_id), PRIMARY KEY(questions_id, types_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE questions_types ADD CONSTRAINT FK_DC48194C8EB23357 FOREIGN KEY (types_id) REFERENCES types (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE questions_types ADD CONSTRAINT FK_DC48194CBCB134CE FOREIGN KEY (questions_id) REFERENCES questions (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE questions DROP FOREIGN KEY FK_8ADC54D5C54C8C93');
        $this->addSql('DROP INDEX IDX_8ADC54D5C54C8C93 ON questions');
        $this->addSql('ALTER TABLE questions DROP type_id');
    }
}
