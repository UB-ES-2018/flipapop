<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181209160653 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('INSERT INTO category(ID, NAME) VALUES (1, "Deporte y Ocio")');
        $this->addSql('INSERT INTO category(ID, NAME) VALUES (2, "Hogar y Jardin")');
        $this->addSql('INSERT INTO category(ID, NAME) VALUES (3, "Informatica y Electronica")');
        $this->addSql('INSERT INTO category(ID, NAME) VALUES (4, "Moda y Accesorios")');
        $this->addSql('INSERT INTO category(ID, NAME) VALUES (5, "Niños y Bebes")');
        $this->addSql('INSERT INTO category(ID, NAME) VALUES (6, "TV, Audio y Foto")');
        $this->addSql('INSERT INTO category(ID, NAME) VALUES (7, "Otros")');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
    }

}
