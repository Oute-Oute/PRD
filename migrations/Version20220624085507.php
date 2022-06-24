<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220624085507 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE activity (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, activityname VARCHAR(255) NOT NULL, duration INTEGER NOT NULL)');
        $this->addSql('CREATE TABLE activity_circuit (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, activity_id INTEGER NOT NULL, circuit_id INTEGER NOT NULL)');
        $this->addSql('CREATE INDEX IDX_1377854681C06096 ON activity_circuit (activity_id)');
        $this->addSql('CREATE INDEX IDX_13778546CF2182C8 ON activity_circuit (circuit_id)');
        $this->addSql('CREATE TABLE activity_resource_type (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, activity_id INTEGER NOT NULL, resourcetype_id INTEGER NOT NULL)');
        $this->addSql('CREATE INDEX IDX_822C3E5781C06096 ON activity_resource_type (activity_id)');
        $this->addSql('CREATE INDEX IDX_822C3E57F48381EA ON activity_resource_type (resourcetype_id)');
        $this->addSql('CREATE TABLE circuit (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, target INTEGER DEFAULT NULL, circuitname VARCHAR(255) NOT NULL, circuittype VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE circuit_patient (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, patient_id INTEGER NOT NULL, circuit_id INTEGER NOT NULL)');
        $this->addSql('CREATE INDEX IDX_2707810E6B899279 ON circuit_patient (patient_id)');
        $this->addSql('CREATE INDEX IDX_2707810ECF2182C8 ON circuit_patient (circuit_id)');
        $this->addSql('CREATE TABLE complete_activity (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, activity_id INTEGER NOT NULL, patient_id INTEGER NOT NULL, startdate DATE NOT NULL, enddate DATE NOT NULL)');
        $this->addSql('CREATE INDEX IDX_33545D4C81C06096 ON complete_activity (activity_id)');
        $this->addSql('CREATE INDEX IDX_33545D4C6B899279 ON complete_activity (patient_id)');
        $this->addSql('CREATE TABLE complete_activity_resource (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, completeactivity_id INTEGER NOT NULL, resource_id INTEGER NOT NULL)');
        $this->addSql('CREATE INDEX IDX_31A01FD592152FF4 ON complete_activity_resource (completeactivity_id)');
        $this->addSql('CREATE INDEX IDX_31A01FD589329D25 ON complete_activity_resource (resource_id)');
        $this->addSql('CREATE TABLE modification (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, datemodif DATE NOT NULL, modified BOOLEAN NOT NULL)');
        $this->addSql('CREATE INDEX IDX_EF6425D2A76ED395 ON modification (user_id)');
        $this->addSql('CREATE TABLE patient (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, lastname VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE resource (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, resourcetype_id INTEGER NOT NULL, resourcename VARCHAR(255) NOT NULL, able BOOLEAN NOT NULL)');
        $this->addSql('CREATE INDEX IDX_BC91F416F48381EA ON resource (resourcetype_id)');
        $this->addSql('CREATE TABLE resource_type (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, type VARCHAR(255) NOT NULL, category VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON user (username)');
        $this->addSql('CREATE TABLE messenger_messages (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, body CLOB NOT NULL, headers CLOB NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL)');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE activity');
        $this->addSql('DROP TABLE activity_circuit');
        $this->addSql('DROP TABLE activity_resource_type');
        $this->addSql('DROP TABLE circuit');
        $this->addSql('DROP TABLE circuit_patient');
        $this->addSql('DROP TABLE complete_activity');
        $this->addSql('DROP TABLE complete_activity_resource');
        $this->addSql('DROP TABLE modification');
        $this->addSql('DROP TABLE patient');
        $this->addSql('DROP TABLE resource');
        $this->addSql('DROP TABLE resource_type');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
