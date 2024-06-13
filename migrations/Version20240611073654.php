<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240611073654 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tecaj_prema_euro (id INT AUTO_INCREMENT NOT NULL, valute_id INT NOT NULL, tecaj DOUBLE PRECISION NOT NULL, na_dan DATE NOT NULL, INDEX IDX_21F4E8E2F7A2E92B (valute_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tecaj_prema_euro ADD CONSTRAINT FK_21F4E8E2F7A2E92B FOREIGN KEY (valute_id) REFERENCES valute (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tecaj_prema_euro DROP FOREIGN KEY FK_21F4E8E2F7A2E92B');
        $this->addSql('DROP TABLE tecaj_prema_euro');
    }
}
