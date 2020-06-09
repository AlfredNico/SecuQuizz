<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200609014609 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE competence (id INT AUTO_INCREMENT NOT NULL, article_id INT NOT NULL, title VARCHAR(200) NOT NULL, INDEX IDX_94D4687F7294869C (article_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE questions_competence (questions_id INT NOT NULL, competence_id INT NOT NULL, INDEX IDX_9A0D1F3BCB134CE (questions_id), INDEX IDX_9A0D1F315761DAB (competence_id), PRIMARY KEY(questions_id, competence_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE competence ADD CONSTRAINT FK_94D4687F7294869C FOREIGN KEY (article_id) REFERENCES families (id)');
        $this->addSql('ALTER TABLE questions_competence ADD CONSTRAINT FK_9A0D1F3BCB134CE FOREIGN KEY (questions_id) REFERENCES questions (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE questions_competence ADD CONSTRAINT FK_9A0D1F315761DAB FOREIGN KEY (competence_id) REFERENCES competence (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE families ADD etat TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE questions ADD article_id INT NOT NULL, CHANGE attached attached VARCHAR(255) DEFAULT NULL, CHANGE motif motif VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE questions ADD CONSTRAINT FK_8ADC54D57294869C FOREIGN KEY (article_id) REFERENCES families (id)');
        $this->addSql('CREATE INDEX IDX_8ADC54D57294869C ON questions (article_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE questions_competence DROP FOREIGN KEY FK_9A0D1F315761DAB');
        $this->addSql('DROP TABLE competence');
        $this->addSql('DROP TABLE questions_competence');
        $this->addSql('ALTER TABLE families DROP etat');
        $this->addSql('ALTER TABLE questions DROP FOREIGN KEY FK_8ADC54D57294869C');
        $this->addSql('DROP INDEX IDX_8ADC54D57294869C ON questions');
        $this->addSql('ALTER TABLE questions DROP article_id, CHANGE attached attached VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE motif motif VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
