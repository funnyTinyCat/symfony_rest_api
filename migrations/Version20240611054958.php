<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240611054958 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE artikli (id INT AUTO_INCREMENT NOT NULL, jedinica_mjere_id INT DEFAULT NULL, valute_id INT DEFAULT NULL, naziv VARCHAR(60) NOT NULL, stanje_na_skladistu DOUBLE PRECISION NOT NULL, cijena DOUBLE PRECISION DEFAULT NULL, trazeno_stanje DOUBLE PRECISION DEFAULT NULL, cijena_unabavi DOUBLE PRECISION DEFAULT NULL, krajnji_rok_nabave DATE DEFAULT NULL, INDEX IDX_59F13F5101A78A1 (jedinica_mjere_id), INDEX IDX_59F13F5F7A2E92B (valute_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE artikli ADD CONSTRAINT FK_59F13F5101A78A1 FOREIGN KEY (jedinica_mjere_id) REFERENCES jedinice_mjere (id)');
        $this->addSql('ALTER TABLE artikli ADD CONSTRAINT FK_59F13F5F7A2E92B FOREIGN KEY (valute_id) REFERENCES valute (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE artikli DROP FOREIGN KEY FK_59F13F5101A78A1');
        $this->addSql('ALTER TABLE artikli DROP FOREIGN KEY FK_59F13F5F7A2E92B');
        $this->addSql('DROP TABLE artikli');
    }
}
