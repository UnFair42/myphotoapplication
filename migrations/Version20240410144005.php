<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240410144005 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE customer DROP FOREIGN KEY FK_81398E09A76ED395');
        $this->addSql('DROP INDEX UNIQ_81398E09A76ED395 ON customer');
        $this->addSql('ALTER TABLE customer ADD user VARCHAR(255) NOT NULL, DROP user_id');
        $this->addSql('ALTER TABLE `order` CHANGE created_at created_at DATETIME DEFAULT \'2024-04-09\' NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE photo CHANGE created_at created_at DATETIME DEFAULT \'2024-04-09\' NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE customer ADD user_id INT DEFAULT NULL, DROP user');
        $this->addSql('ALTER TABLE customer ADD CONSTRAINT FK_81398E09A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_81398E09A76ED395 ON customer (user_id)');
        $this->addSql('ALTER TABLE `order` CHANGE created_at created_at DATETIME DEFAULT \'2024-04-09 00:00:00\' NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE photo CHANGE created_at created_at DATETIME DEFAULT \'2024-04-09 00:00:00\' NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }
}
