<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250506160709 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE colab (id INT AUTO_INCREMENT NOT NULL, nomprenom VARCHAR(255) NOT NULL, fonction VARCHAR(255) NOT NULL, nomentreprise VARCHAR(255) NOT NULL, site VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, tel VARCHAR(255) NOT NULL, linkedin VARCHAR(255) NOT NULL, pays VARCHAR(255) NOT NULL, typetalent VARCHAR(255) NOT NULL, secteur VARCHAR(255) NOT NULL, competence VARCHAR(255) NOT NULL, typecolab VARCHAR(255) NOT NULL, durecolab VARCHAR(255) NOT NULL, message LONGTEXT NOT NULL, ficher VARCHAR(255) NOT NULL, politique VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user CHANGE roles roles JSON NOT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP TABLE colab
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_bin`
        SQL);
    }
}
