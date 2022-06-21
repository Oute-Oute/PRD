<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220621090315 extends AbstractMigration
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
        $this->addSql('CREATE TABLE activity_resource_type (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, activity_id_id INTEGER DEFAULT NULL, resource_type_id_id INTEGER DEFAULT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_822C3E576146A8E4 ON activity_resource_type (activity_id_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_822C3E5711505E26 ON activity_resource_type (resource_type_id_id)');
        $this->addSql('CREATE TABLE circuit (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, circuit_type_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE INDEX IDX_1325F3A6F891DA76 ON circuit (circuit_type_id)');
        $this->addSql('CREATE TABLE circuit_event (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, circuit_id_id INTEGER DEFAULT NULL, activity_id_id INTEGER DEFAULT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F8CB7C60BC21E890 ON circuit_event (circuit_id_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F8CB7C606146A8E4 ON circuit_event (activity_id_id)');
        $this->addSql('CREATE TABLE circuit_type (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE modification (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, date DATE NOT NULL, modified VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE patient (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, patient_id VARCHAR(255) NOT NULL, surname VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE patient_circuit_resource (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, patient_id_id INTEGER DEFAULT NULL, circuit_id_id INTEGER DEFAULT NULL, resource_id_id INTEGER NOT NULL, start_date_time DATETIME NOT NULL, end_date_time DATETIME NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C3279072EA724598 ON patient_circuit_resource (patient_id_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C3279072BC21E890 ON patient_circuit_resource (circuit_id_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C327907254FFE465 ON patient_circuit_resource (resource_id_id)');
        $this->addSql('CREATE TABLE resource (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, resource_type_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE INDEX IDX_BC91F41698EC6B7B ON resource (resource_type_id)');
        $this->addSql('CREATE TABLE resource_type (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, category VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE user_modification (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id_id INTEGER DEFAULT NULL, modification_id_id INTEGER DEFAULT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6FA859D09D86650F ON user_modification (user_id_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6FA859D0B5519852 ON user_modification (modification_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE activity');
        $this->addSql('DROP TABLE activity_circuit');
        $this->addSql('DROP TABLE activity_resource_type');
        $this->addSql('DROP TABLE circuit');
        $this->addSql('DROP TABLE circuit_event');
        $this->addSql('DROP TABLE circuit_type');
        $this->addSql('DROP TABLE modification');
        $this->addSql('DROP TABLE patient');
        $this->addSql('DROP TABLE patient_circuit_resource');
        $this->addSql('DROP TABLE resource');
        $this->addSql('DROP TABLE resource_type');
        $this->addSql('DROP TABLE user_modification');
    }
}
