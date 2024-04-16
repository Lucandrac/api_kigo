<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240416090809 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE profil_skill (profil_id INT NOT NULL, skill_id INT NOT NULL, INDEX IDX_10313F33275ED078 (profil_id), INDEX IDX_10313F335585C142 (skill_id), PRIMARY KEY(profil_id, skill_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE profil_skill ADD CONSTRAINT FK_10313F33275ED078 FOREIGN KEY (profil_id) REFERENCES profil (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE profil_skill ADD CONSTRAINT FK_10313F335585C142 FOREIGN KEY (skill_id) REFERENCES skill (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE profil_skill DROP FOREIGN KEY FK_10313F33275ED078');
        $this->addSql('ALTER TABLE profil_skill DROP FOREIGN KEY FK_10313F335585C142');
        $this->addSql('DROP TABLE profil_skill');
    }
}
