<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201004193040 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE chapter (uuid VARCHAR(255) NOT NULL, course_uuid VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(uuid))');
        $this->addSql('CREATE INDEX IDX_F981B52E19DD8DA0 ON chapter (course_uuid)');
        $this->addSql('CREATE TABLE course (uuid VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, price INTEGER NOT NULL, PRIMARY KEY(uuid))');
        $this->addSql('CREATE TABLE discount (uuid VARCHAR(255) NOT NULL, scope VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, value INTEGER NOT NULL, maximum_uses INTEGER NOT NULL, PRIMARY KEY(uuid))');
        $this->addSql('CREATE TABLE discount_course (discount_uuid VARCHAR(255) NOT NULL, course_uuid VARCHAR(255) NOT NULL, PRIMARY KEY(discount_uuid, course_uuid))');
        $this->addSql('CREATE INDEX IDX_78DE43864FFD551C ON discount_course (discount_uuid)');
        $this->addSql('CREATE INDEX IDX_78DE438619DD8DA0 ON discount_course (course_uuid)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE chapter');
        $this->addSql('DROP TABLE course');
        $this->addSql('DROP TABLE discount');
        $this->addSql('DROP TABLE discount_course');
    }
}
