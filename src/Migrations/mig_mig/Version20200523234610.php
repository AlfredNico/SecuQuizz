<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200523234610 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE questions_types (questions_id INT NOT NULL, types_id INT NOT NULL, INDEX IDX_DC48194CBCB134CE (questions_id), INDEX IDX_DC48194C8EB23357 (types_id), PRIMARY KEY(questions_id, types_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE questions_types ADD CONSTRAINT FK_DC48194CBCB134CE FOREIGN KEY (questions_id) REFERENCES questions (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE questions_types ADD CONSTRAINT FK_DC48194C8EB23357 FOREIGN KEY (types_id) REFERENCES types (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE answers ADD questions_id INT NOT NULL');
        $this->addSql('ALTER TABLE answers ADD CONSTRAINT FK_50D0C606BCB134CE FOREIGN KEY (questions_id) REFERENCES questions (id)');
        $this->addSql('CREATE INDEX IDX_50D0C606BCB134CE ON answers (questions_id)');
        $this->addSql('ALTER TABLE competences ADD families_id INT NOT NULL');
        $this->addSql('ALTER TABLE competences ADD CONSTRAINT FK_DB2077CE5DFECCD4 FOREIGN KEY (families_id) REFERENCES families (id)');
        $this->addSql('CREATE INDEX IDX_DB2077CE5DFECCD4 ON competences (families_id)');
        $this->addSql('ALTER TABLE families ADD users_id INT NOT NULL');
        $this->addSql('ALTER TABLE families ADD CONSTRAINT FK_995F3FCC67B3B43D FOREIGN KEY (users_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_995F3FCC67B3B43D ON families (users_id)');
        $this->addSql('ALTER TABLE questions ADD users_id INT NOT NULL, ADD competences_id INT NOT NULL');
        $this->addSql('ALTER TABLE questions ADD CONSTRAINT FK_8ADC54D567B3B43D FOREIGN KEY (users_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE questions ADD CONSTRAINT FK_8ADC54D5A660B158 FOREIGN KEY (competences_id) REFERENCES competences (id)');
        $this->addSql('CREATE INDEX IDX_8ADC54D567B3B43D ON questions (users_id)');
        $this->addSql('CREATE INDEX IDX_8ADC54D5A660B158 ON questions (competences_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE questions_types');
        $this->addSql('ALTER TABLE answers DROP FOREIGN KEY FK_50D0C606BCB134CE');
        $this->addSql('DROP INDEX IDX_50D0C606BCB134CE ON answers');
        $this->addSql('ALTER TABLE answers DROP questions_id');
        $this->addSql('ALTER TABLE competences DROP FOREIGN KEY FK_DB2077CE5DFECCD4');
        $this->addSql('DROP INDEX IDX_DB2077CE5DFECCD4 ON competences');
        $this->addSql('ALTER TABLE competences DROP families_id');
        $this->addSql('ALTER TABLE families DROP FOREIGN KEY FK_995F3FCC67B3B43D');
        $this->addSql('DROP INDEX IDX_995F3FCC67B3B43D ON families');
        $this->addSql('ALTER TABLE families DROP users_id');
        $this->addSql('ALTER TABLE questions DROP FOREIGN KEY FK_8ADC54D567B3B43D');
        $this->addSql('ALTER TABLE questions DROP FOREIGN KEY FK_8ADC54D5A660B158');
        $this->addSql('DROP INDEX IDX_8ADC54D567B3B43D ON questions');
        $this->addSql('DROP INDEX IDX_8ADC54D5A660B158 ON questions');
        $this->addSql('ALTER TABLE questions DROP users_id, DROP competences_id');
    }
}
