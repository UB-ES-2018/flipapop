<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181021175552 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE product ADD usuario_id INT NOT NULL');
        $this->addSql('ALTER TABLE product ADD updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE product ADD image_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD image_original_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD image_mime_type VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD image_size INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD image_dimensions TEXT DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN product.image_dimensions IS \'(DC2Type:simple_array)\'');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADDB38439E FOREIGN KEY (usuario_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_D34A04ADDB38439E ON product (usuario_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE product DROP CONSTRAINT FK_D34A04ADDB38439E');
        $this->addSql('DROP INDEX IDX_D34A04ADDB38439E');
        $this->addSql('ALTER TABLE product DROP usuario_id');
        $this->addSql('ALTER TABLE product DROP updated_at');
        $this->addSql('ALTER TABLE product DROP image_name');
        $this->addSql('ALTER TABLE product DROP image_original_name');
        $this->addSql('ALTER TABLE product DROP image_mime_type');
        $this->addSql('ALTER TABLE product DROP image_size');
        $this->addSql('ALTER TABLE product DROP image_dimensions');
    }
}
