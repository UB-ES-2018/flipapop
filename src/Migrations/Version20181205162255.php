<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181205162255 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE user_review_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE user_review (id INT NOT NULL, usuario_id INT NOT NULL, reviwer_id INT NOT NULL, text VARCHAR(2000) NOT NULL, stars INT NOT NULL, fecha_creacion TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_1C119AFBDB38439E ON user_review (usuario_id)');
        $this->addSql('CREATE INDEX IDX_1C119AFB6C10D968 ON user_review (reviwer_id)');
        $this->addSql('ALTER TABLE user_review ADD CONSTRAINT FK_1C119AFBDB38439E FOREIGN KEY (usuario_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_review ADD CONSTRAINT FK_1C119AFB6C10D968 FOREIGN KEY (reviwer_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product ALTER sold SET NOT NULL');
        $this->addSql('ALTER TABLE product ALTER num_likes DROP DEFAULT');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE user_review_id_seq CASCADE');
        $this->addSql('DROP TABLE user_review');
        $this->addSql('ALTER TABLE product ALTER num_likes SET DEFAULT 0');
        $this->addSql('ALTER TABLE product ALTER sold DROP NOT NULL');
    }
}
