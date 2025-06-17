<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250508035902 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE user CHANGE roles roles JSON NOT NULL, CHANGE photoprofil photoprofil VARCHAR(255) DEFAULT NULL, CHANGE lieu lieu VARCHAR(255) DEFAULT NULL, CHANGE apropos apropos LONGTEXT DEFAULT NULL, CHANGE competences competences JSON NOT NULL, CHANGE tel tel VARCHAR(255) DEFAULT NULL, CHANGE facebook facebook VARCHAR(255) DEFAULT NULL, CHANGE linkedin linkedin VARCHAR(255) DEFAULT NULL, CHANGE twiter twiter VARCHAR(255) DEFAULT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE user CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_bin`, CHANGE photoprofil photoprofil VARCHAR(255) NOT NULL, CHANGE lieu lieu VARCHAR(255) NOT NULL, CHANGE apropos apropos VARCHAR(255) NOT NULL, CHANGE competences competences VARCHAR(255) NOT NULL, CHANGE tel tel VARCHAR(255) NOT NULL, CHANGE facebook facebook VARCHAR(255) NOT NULL, CHANGE linkedin linkedin VARCHAR(255) NOT NULL, CHANGE twiter twiter VARCHAR(255) NOT NULL
        SQL);
    }
}
