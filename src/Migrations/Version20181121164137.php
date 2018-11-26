<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181121164137 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE comentario_producto_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE comentario_producto (id INT NOT NULL, product_id INT NOT NULL, usuario_id INT NOT NULL, padre INT DEFAULT NULL, texto VARCHAR(4000) NOT NULL, fecha_creacion TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2B8C0AC4584665A ON comentario_producto (product_id)');
        $this->addSql('CREATE INDEX IDX_2B8C0ACDB38439E ON comentario_producto (usuario_id)');
        $this->addSql('ALTER TABLE comentario_producto ADD CONSTRAINT FK_2B8C0AC4584665A FOREIGN KEY (product_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE comentario_producto ADD CONSTRAINT FK_2B8C0ACDB38439E FOREIGN KEY (usuario_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product ALTER visibility DROP DEFAULT');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE comentario_producto_id_seq CASCADE');
        $this->addSql('DROP TABLE comentario_producto');
        $this->addSql('ALTER TABLE product ALTER visibility SET DEFAULT 1');
    }
}
