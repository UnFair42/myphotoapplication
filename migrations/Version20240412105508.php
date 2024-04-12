<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240412105508 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` ADD modified_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE created_at created_at DATETIME DEFAULT \'2024-04-09\' NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE order_item ADD photo_id_id INT NOT NULL, ADD order_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F09C51599E0 FOREIGN KEY (photo_id_id) REFERENCES photo (id)');
        $this->addSql('ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F09FCDAEAAA FOREIGN KEY (order_id_id) REFERENCES `order` (id)');
        $this->addSql('CREATE INDEX IDX_52EA1F09C51599E0 ON order_item (photo_id_id)');
        $this->addSql('CREATE INDEX IDX_52EA1F09FCDAEAAA ON order_item (order_id_id)');
        $this->addSql('ALTER TABLE photo CHANGE created_at created_at DATETIME DEFAULT \'2024-04-09\' NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` DROP modified_at, CHANGE created_at created_at DATETIME DEFAULT \'2024-04-09 00:00:00\' NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE order_item DROP FOREIGN KEY FK_52EA1F09C51599E0');
        $this->addSql('ALTER TABLE order_item DROP FOREIGN KEY FK_52EA1F09FCDAEAAA');
        $this->addSql('DROP INDEX IDX_52EA1F09C51599E0 ON order_item');
        $this->addSql('DROP INDEX IDX_52EA1F09FCDAEAAA ON order_item');
        $this->addSql('ALTER TABLE order_item DROP photo_id_id, DROP order_id_id');
        $this->addSql('ALTER TABLE photo CHANGE created_at created_at DATETIME DEFAULT \'2024-04-09 00:00:00\' NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }
}
