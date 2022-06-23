<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220623095701 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE activity (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, duration INTEGER NOT NULL)');
        $this->addSql('CREATE TABLE activity_circuit (activity_id INTEGER NOT NULL, circuit_id INTEGER NOT NULL, PRIMARY KEY(activity_id, circuit_id))');
        $this->addSql('CREATE INDEX IDX_1377854681C06096 ON activity_circuit (activity_id)');
        $this->addSql('CREATE INDEX IDX_13778546CF2182C8 ON activity_circuit (circuit_id)');
        $this->addSql('CREATE TABLE activity_resource_type (activity_id INTEGER NOT NULL, resource_type_id INTEGER NOT NULL, PRIMARY KEY(activity_id, resource_type_id))');
        $this->addSql('CREATE INDEX IDX_822C3E5781C06096 ON activity_resource_type (activity_id)');
        $this->addSql('CREATE INDEX IDX_822C3E5798EC6B7B ON activity_resource_type (resource_type_id)');
        $this->addSql('CREATE TABLE circuit (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, circuit_type VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE circuit_type (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE modification (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, date DATE NOT NULL, modified VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE patient (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, patient_id VARCHAR(255) NOT NULL, surname VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE patient_activity_resource (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, start_datetime DATETIME NOT NULL, end_datetime DATETIME NOT NULL)');
        $this->addSql('CREATE TABLE patient_activity_resource_patient (patient_activity_resource_id INTEGER NOT NULL, patient_id INTEGER NOT NULL, PRIMARY KEY(patient_activity_resource_id, patient_id))');
        $this->addSql('CREATE INDEX IDX_CBE93390EFE8671E ON patient_activity_resource_patient (patient_activity_resource_id)');
        $this->addSql('CREATE INDEX IDX_CBE933906B899279 ON patient_activity_resource_patient (patient_id)');
        $this->addSql('CREATE TABLE patient_activity_resource_activity (patient_activity_resource_id INTEGER NOT NULL, activity_id INTEGER NOT NULL, PRIMARY KEY(patient_activity_resource_id, activity_id))');
        $this->addSql('CREATE INDEX IDX_6B72920AEFE8671E ON patient_activity_resource_activity (patient_activity_resource_id)');
        $this->addSql('CREATE INDEX IDX_6B72920A81C06096 ON patient_activity_resource_activity (activity_id)');
        $this->addSql('CREATE TABLE patient_activity_resource_resource (patient_activity_resource_id INTEGER NOT NULL, resource_id INTEGER NOT NULL, PRIMARY KEY(patient_activity_resource_id, resource_id))');
        $this->addSql('CREATE INDEX IDX_7B976F46EFE8671E ON patient_activity_resource_resource (patient_activity_resource_id)');
        $this->addSql('CREATE INDEX IDX_7B976F4689329D25 ON patient_activity_resource_resource (resource_id)');
        $this->addSql('CREATE TABLE resource (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, resource_type_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE INDEX IDX_BC91F41698EC6B7B ON resource (resource_type_id)');
        $this->addSql('CREATE TABLE resource_type (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, category VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE resource_type_activity (resource_type_id INTEGER NOT NULL, activity_id INTEGER NOT NULL, PRIMARY KEY(resource_type_id, activity_id))');
        $this->addSql('CREATE INDEX IDX_2867086098EC6B7B ON resource_type_activity (resource_type_id)');
        $this->addSql('CREATE INDEX IDX_2867086081C06096 ON resource_type_activity (activity_id)');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, login VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE user_modification (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id_id INTEGER DEFAULT NULL, modification_id_id INTEGER DEFAULT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6FA859D09D86650F ON user_modification (user_id_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6FA859D0B5519852 ON user_modification (modification_id_id)');
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
        $this->addSql('DROP TABLE circuit_type');
        $this->addSql('DROP TABLE modification');
        $this->addSql('DROP TABLE patient');
        $this->addSql('DROP TABLE patient_activity_resource');
        $this->addSql('DROP TABLE patient_activity_resource_patient');
        $this->addSql('DROP TABLE patient_activity_resource_activity');
        $this->addSql('DROP TABLE patient_activity_resource_resource');
        $this->addSql('DROP TABLE resource');
        $this->addSql('DROP TABLE resource_type');
        $this->addSql('DROP TABLE resource_type_activity');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_modification');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
