<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220103120739 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE user_class_teacher (user_class_id INT NOT NULL, teacher_id INT NOT NULL, INDEX IDX_9B0D6C79A8DEF10 (user_class_id), INDEX IDX_9B0D6C7941807E1D (teacher_id), PRIMARY KEY(user_class_id, teacher_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_class_teacher ADD CONSTRAINT FK_9B0D6C79A8DEF10 FOREIGN KEY (user_class_id) REFERENCES user_class (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_class_teacher ADD CONSTRAINT FK_9B0D6C7941807E1D FOREIGN KEY (teacher_id) REFERENCES teacher (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_class DROP FOREIGN KEY FK_F89E2C741807E1D');
        $this->addSql('DROP INDEX UNIQ_F89E2C741807E1D ON user_class');
        $this->addSql('ALTER TABLE user_class DROP teacher_id');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE user_class_teacher');
        $this->addSql('ALTER TABLE user_class ADD teacher_id INT NOT NULL');
        $this->addSql('ALTER TABLE user_class ADD CONSTRAINT FK_F89E2C741807E1D FOREIGN KEY (teacher_id) REFERENCES teacher (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F89E2C741807E1D ON user_class (teacher_id)');
    }
}
