<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240404092924 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE contact (id INT AUTO_INCREMENT NOT NULL, type_id INT NOT NULL, value VARCHAR(255) NOT NULL, INDEX IDX_4C62E638C54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE media (id INT AUTO_INCREMENT NOT NULL, url VARCHAR(255) NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE profil (id INT AUTO_INCREMENT NOT NULL, filiere_id INT DEFAULT NULL, user_id_id INT NOT NULL, biography LONGTEXT DEFAULT NULL, INDEX IDX_E6D6B297180AA129 (filiere_id), UNIQUE INDEX UNIQ_E6D6B2979D86650F (user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project_profil (project_id INT NOT NULL, profil_id INT NOT NULL, INDEX IDX_F17259DD166D1F9C (project_id), INDEX IDX_F17259DD275ED078 (profil_id), PRIMARY KEY(project_id, profil_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_contact (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE contact ADD CONSTRAINT FK_4C62E638C54C8C93 FOREIGN KEY (type_id) REFERENCES type_contact (id)');
        $this->addSql('ALTER TABLE profil ADD CONSTRAINT FK_E6D6B297180AA129 FOREIGN KEY (filiere_id) REFERENCES filiere (id)');
        $this->addSql('ALTER TABLE profil ADD CONSTRAINT FK_E6D6B2979D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE project_profil ADD CONSTRAINT FK_F17259DD166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE project_profil ADD CONSTRAINT FK_F17259DD275ED078 FOREIGN KEY (profil_id) REFERENCES profil (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D4296D31F');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D61220EA6');
        $this->addSql('DROP INDEX IDX_5A8A6C8D4296D31F ON post');
        $this->addSql('ALTER TABLE post ADD titre VARCHAR(255) NOT NULL, ADD date_creation DATE NOT NULL, ADD date_modified DATE DEFAULT NULL, DROP created_at, DROP is_active, CHANGE creator_id creator_id INT DEFAULT NULL, CHANGE genre_id media_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DEA9FDD75 FOREIGN KEY (media_id) REFERENCES media (id)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D61220EA6 FOREIGN KEY (creator_id) REFERENCES profil (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5A8A6C8DEA9FDD75 ON post (media_id)');
        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EE61220EA6');
        $this->addSql('DROP INDEX IDX_2FB3D0EE61220EA6 ON project');
        $this->addSql('ALTER TABLE project ADD is_finished TINYINT(1) NOT NULL, DROP title, DROP description, DROP status, CHANGE creator_id post_id INT NOT NULL, CHANGE is_active is_open TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EE4B89032C FOREIGN KEY (post_id) REFERENCES post (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2FB3D0EE4B89032C ON project (post_id)');
        $this->addSql('ALTER TABLE user DROP phone, DROP teams, DROP biography, CHANGE is_active is_admin TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DEA9FDD75');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D61220EA6');
        $this->addSql('ALTER TABLE contact DROP FOREIGN KEY FK_4C62E638C54C8C93');
        $this->addSql('ALTER TABLE profil DROP FOREIGN KEY FK_E6D6B297180AA129');
        $this->addSql('ALTER TABLE profil DROP FOREIGN KEY FK_E6D6B2979D86650F');
        $this->addSql('ALTER TABLE project_profil DROP FOREIGN KEY FK_F17259DD166D1F9C');
        $this->addSql('ALTER TABLE project_profil DROP FOREIGN KEY FK_F17259DD275ED078');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE media');
        $this->addSql('DROP TABLE profil');
        $this->addSql('DROP TABLE project_profil');
        $this->addSql('DROP TABLE type_contact');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D61220EA6');
        $this->addSql('DROP INDEX UNIQ_5A8A6C8DEA9FDD75 ON post');
        $this->addSql('ALTER TABLE post ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD is_active TINYINT(1) NOT NULL, DROP titre, DROP date_creation, DROP date_modified, CHANGE creator_id creator_id INT NOT NULL, CHANGE media_id genre_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D4296D31F FOREIGN KEY (genre_id) REFERENCES genre (id)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D61220EA6 FOREIGN KEY (creator_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_5A8A6C8D4296D31F ON post (genre_id)');
        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EE4B89032C');
        $this->addSql('DROP INDEX UNIQ_2FB3D0EE4B89032C ON project');
        $this->addSql('ALTER TABLE project ADD title VARCHAR(255) NOT NULL, ADD description LONGTEXT NOT NULL, ADD status VARCHAR(255) NOT NULL, ADD is_active TINYINT(1) NOT NULL, DROP is_open, DROP is_finished, CHANGE post_id creator_id INT NOT NULL');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EE61220EA6 FOREIGN KEY (creator_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_2FB3D0EE61220EA6 ON project (creator_id)');
        $this->addSql('ALTER TABLE user ADD phone VARCHAR(255) NOT NULL, ADD teams VARCHAR(255) NOT NULL, ADD biography LONGTEXT DEFAULT NULL, CHANGE is_admin is_active TINYINT(1) NOT NULL');
    }
}
