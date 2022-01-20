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
        $this->addSql('ALTER TABLE classes DROP FOREIGN KEY FK_2ED7EC541807E1D');

        $this->addSql('ALTER TABLE classes RENAME TO user_class');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F89E2C741807E1D ON user_class (teacher_id)');
        $this->addSql('ALTER TABLE user_class ADD CONSTRAINT FK_F89E2C741807E1D FOREIGN KEY (teacher_id) REFERENCES teacher (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE user_class DROP FOREIGN KEY FK_F89E2C741807E1D');
        $this->addSql('DROP INDEX UNIQ_F89E2C741807E1D ON user_class');

        $this->addSql('ALTER TABLE user_class  RENAME TO classes');
        $this->addSql('ALTER TABLE classes ADD CONSTRAINT FK_2ED7EC541807E1D FOREIGN KEY (teacher_id) REFERENCES teacher (id)');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF339E225B24 FOREIGN KEY (classes_id) REFERENCES user_class (id)');
    }
}
