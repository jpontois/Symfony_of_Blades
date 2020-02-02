<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200202030627 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE game_entity ADD editor_id INT DEFAULT NULL, DROP editor');
        $this->addSql('ALTER TABLE game_entity ADD CONSTRAINT FK_731DE9A06995AC4C FOREIGN KEY (editor_id) REFERENCES user_entity (id)');
        $this->addSql('CREATE INDEX IDX_731DE9A06995AC4C ON game_entity (editor_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE game_entity DROP FOREIGN KEY FK_731DE9A06995AC4C');
        $this->addSql('DROP INDEX IDX_731DE9A06995AC4C ON game_entity');
        $this->addSql('ALTER TABLE game_entity ADD editor VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, DROP editor_id');
    }
}
