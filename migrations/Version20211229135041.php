<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20211229135041 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF339E225B24 FOREIGN KEY (classes_id) REFERENCES user_class (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE student DROP FOREIGN KEY FK_B723AF339E225B24');
    }
}
