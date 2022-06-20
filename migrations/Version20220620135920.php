<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220620135920 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE appointment (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, circuit_id INTEGER DEFAULT NULL, patient_id INTEGER DEFAULT NULL)');
        $this->addSql('CREATE INDEX IDX_FE38F844CF2182C8 ON appointment (circuit_id)');
        $this->addSql('CREATE INDEX IDX_FE38F8446B899279 ON appointment (patient_id)');
        $this->addSql('CREATE TABLE circuit (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE event (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE event_resource (event_id INTEGER NOT NULL, resource_id INTEGER NOT NULL, PRIMARY KEY(event_id, resource_id))');
        $this->addSql('CREATE INDEX IDX_FA7D1DC671F7E88B ON event_resource (event_id)');
        $this->addSql('CREATE INDEX IDX_FA7D1DC689329D25 ON event_resource (resource_id)');
        $this->addSql('CREATE TABLE event_circuit (event_id INTEGER NOT NULL, circuit_id INTEGER NOT NULL, PRIMARY KEY(event_id, circuit_id))');
        $this->addSql('CREATE INDEX IDX_96725A0971F7E88B ON event_circuit (event_id)');
        $this->addSql('CREATE INDEX IDX_96725A09CF2182C8 ON event_circuit (circuit_id)');
        $this->addSql('CREATE TABLE patient (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, surname VARCHAR(255) NOT NULL, patient_id VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE resource (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, login VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE messenger_messages (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, body CLOB NOT NULL, headers CLOB NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL)');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE appointment');
        $this->addSql('DROP TABLE circuit');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE event_resource');
        $this->addSql('DROP TABLE event_circuit');
        $this->addSql('DROP TABLE patient');
        $this->addSql('DROP TABLE resource');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
