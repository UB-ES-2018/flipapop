<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181024214602 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE product ALTER updated_at DROP NOT NULL');
        $this->addSql('ALTER TABLE users ADD description TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE users ADD updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE users ADD image_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE users ADD image_original_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE users ADD image_mime_type VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE users ADD image_size INT DEFAULT NULL');
        $this->addSql('ALTER TABLE users ADD image_dimensions TEXT DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN users.image_dimensions IS \'(DC2Type:simple_array)\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE users DROP description');
        $this->addSql('ALTER TABLE users DROP updated_at');
        $this->addSql('ALTER TABLE users DROP image_name');
        $this->addSql('ALTER TABLE users DROP image_original_name');
        $this->addSql('ALTER TABLE users DROP image_mime_type');
        $this->addSql('ALTER TABLE users DROP image_size');
        $this->addSql('ALTER TABLE users DROP image_dimensions');
        $this->addSql('ALTER TABLE product ALTER updated_at SET NOT NULL');
    }
}
