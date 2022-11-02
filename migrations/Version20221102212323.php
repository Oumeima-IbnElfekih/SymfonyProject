<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221102212323 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE hall (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, membre_id INTEGER DEFAULT NULL, description VARCHAR(255) NOT NULL, publie BOOLEAN NOT NULL, CONSTRAINT FK_1B8FA83F6A99F74A FOREIGN KEY (membre_id) REFERENCES membre (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_1B8FA83F6A99F74A ON hall (membre_id)');
        $this->addSql('CREATE TABLE hall_materiel (hall_id INTEGER NOT NULL, materiel_id INTEGER NOT NULL, PRIMARY KEY(hall_id, materiel_id), CONSTRAINT FK_A97D6E8852AFCFD6 FOREIGN KEY (hall_id) REFERENCES hall (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_A97D6E8816880AAF FOREIGN KEY (materiel_id) REFERENCES materiel (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_A97D6E8852AFCFD6 ON hall_materiel (hall_id)');
        $this->addSql('CREATE INDEX IDX_A97D6E8816880AAF ON hall_materiel (materiel_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE hall');
        $this->addSql('DROP TABLE hall_materiel');
    }
}
