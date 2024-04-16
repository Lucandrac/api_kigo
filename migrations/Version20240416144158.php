<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240416144158 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE invite (id INT AUTO_INCREMENT NOT NULL, user_id_id INT NOT NULL, project_id INT NOT NULL, is_active TINYINT(1) NOT NULL, INDEX IDX_C7E210D79D86650F (user_id_id), INDEX IDX_C7E210D7166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE invite ADD CONSTRAINT FK_C7E210D79D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE invite ADD CONSTRAINT FK_C7E210D7166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE invite DROP FOREIGN KEY FK_C7E210D79D86650F');
        $this->addSql('ALTER TABLE invite DROP FOREIGN KEY FK_C7E210D7166D1F9C');
        $this->addSql('DROP TABLE invite');
    }
}
