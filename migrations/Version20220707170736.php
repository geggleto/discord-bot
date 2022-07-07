<?php

declare(strict_types=1);

namespace Bot;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220707170736 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Generate Entity for User';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $table = $schema->createTable('registrations');
        $table->addColumn('uuid', Types::GUID);
        $table->addColumn('discord_id', Types::STRING, ['length' => 18]);

        $table->setPrimaryKey(['uuid']);
        $table->addIndex(['discord_id'], 'reg_discord_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $schema->dropTable('registrations');
    }
}
