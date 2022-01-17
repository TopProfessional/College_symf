<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20211228144517 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE student DROP FOREIGN KEY FK_B723AF339E225B24');
        $this->addSql('CREATE TABLE user_class (id INT AUTO_INCREMENT NOT NULL, teacher_id INT NOT NULL, name VARCHAR(40) NOT NULL, UNIQUE INDEX UNIQ_F89E2C741807E1D (teacher_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_class ADD CONSTRAINT FK_F89E2C741807E1D FOREIGN KEY (teacher_id) REFERENCES teacher (id)');

        $this->addSql('ALTER TABLE classes DROP FOREIGN KEY FK_2ED7EC541807E1D');
        $this->addSql('DROP TABLE classes');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF339E225B24 FOREIGN KEY (classes_id) REFERENCES user_class (id)');
        $this->addSql('DROP TABLE user_class');

        $this->addSql('CREATE TABLE classes (id INT AUTO_INCREMENT NOT NULL, teacher_id INT NOT NULL, name VARCHAR(40) NOT NULL, UNIQUE INDEX UNIQ_2ED7EC541807E1D (teacher_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE classes ADD CONSTRAINT FK_2ED7EC541807E1D FOREIGN KEY (teacher_id) REFERENCES teacher (id)');
    }
}
