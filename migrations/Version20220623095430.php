<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220623095430 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
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
        $this->addSql('DROP TABLE patient_circuit_resource');
        $this->addSql('DROP INDEX IDX_13778546CF2182C8');
        $this->addSql('DROP INDEX IDX_1377854681C06096');
        $this->addSql('CREATE TEMPORARY TABLE __temp__activity_circuit AS SELECT activity_id, circuit_id FROM activity_circuit');
        $this->addSql('DROP TABLE activity_circuit');
        $this->addSql('CREATE TABLE activity_circuit (activity_id INTEGER NOT NULL, circuit_id INTEGER NOT NULL, PRIMARY KEY(activity_id, circuit_id), CONSTRAINT FK_1377854681C06096 FOREIGN KEY (activity_id) REFERENCES activity (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_13778546CF2182C8 FOREIGN KEY (circuit_id) REFERENCES circuit (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO activity_circuit (activity_id, circuit_id) SELECT activity_id, circuit_id FROM __temp__activity_circuit');
        $this->addSql('DROP TABLE __temp__activity_circuit');
        $this->addSql('CREATE INDEX IDX_13778546CF2182C8 ON activity_circuit (circuit_id)');
        $this->addSql('CREATE INDEX IDX_1377854681C06096 ON activity_circuit (activity_id)');
        $this->addSql('DROP INDEX IDX_C7EB9541419CFFC');
        $this->addSql('DROP INDEX IDX_C7EB954DFC9F73');
        $this->addSql('CREATE TEMPORARY TABLE __temp__activity_activity AS SELECT activity_source, activity_target FROM activity_activity');
        $this->addSql('DROP TABLE activity_activity');
        $this->addSql('CREATE TABLE activity_activity (activity_source INTEGER NOT NULL, activity_target INTEGER NOT NULL, PRIMARY KEY(activity_source, activity_target), CONSTRAINT FK_C7EB954DFC9F73 FOREIGN KEY (activity_source) REFERENCES activity (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_C7EB9541419CFFC FOREIGN KEY (activity_target) REFERENCES activity (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO activity_activity (activity_source, activity_target) SELECT activity_source, activity_target FROM __temp__activity_activity');
        $this->addSql('DROP TABLE __temp__activity_activity');
        $this->addSql('CREATE INDEX IDX_C7EB9541419CFFC ON activity_activity (activity_target)');
        $this->addSql('CREATE INDEX IDX_C7EB954DFC9F73 ON activity_activity (activity_source)');
        $this->addSql('DROP INDEX IDX_BC91F41698EC6B7B');
        $this->addSql('CREATE TEMPORARY TABLE __temp__resource AS SELECT id, resource_type_id, name FROM resource');
        $this->addSql('DROP TABLE resource');
        $this->addSql('CREATE TABLE resource (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, resource_type_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, CONSTRAINT FK_BC91F41698EC6B7B FOREIGN KEY (resource_type_id) REFERENCES resource_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO resource (id, resource_type_id, name) SELECT id, resource_type_id, name FROM __temp__resource');
        $this->addSql('DROP TABLE __temp__resource');
        $this->addSql('CREATE INDEX IDX_BC91F41698EC6B7B ON resource (resource_type_id)');
        $this->addSql('DROP INDEX IDX_2867086081C06096');
        $this->addSql('DROP INDEX IDX_2867086098EC6B7B');
        $this->addSql('CREATE TEMPORARY TABLE __temp__resource_type_activity AS SELECT resource_type_id, activity_id FROM resource_type_activity');
        $this->addSql('DROP TABLE resource_type_activity');
        $this->addSql('CREATE TABLE resource_type_activity (resource_type_id INTEGER NOT NULL, activity_id INTEGER NOT NULL, PRIMARY KEY(resource_type_id, activity_id), CONSTRAINT FK_2867086098EC6B7B FOREIGN KEY (resource_type_id) REFERENCES resource_type (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_2867086081C06096 FOREIGN KEY (activity_id) REFERENCES activity (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO resource_type_activity (resource_type_id, activity_id) SELECT resource_type_id, activity_id FROM __temp__resource_type_activity');
        $this->addSql('DROP TABLE __temp__resource_type_activity');
        $this->addSql('CREATE INDEX IDX_2867086081C06096 ON resource_type_activity (activity_id)');
        $this->addSql('CREATE INDEX IDX_2867086098EC6B7B ON resource_type_activity (resource_type_id)');
        $this->addSql('DROP INDEX UNIQ_6FA859D0B5519852');
        $this->addSql('DROP INDEX UNIQ_6FA859D09D86650F');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user_modification AS SELECT id, user_id_id, modification_id_id FROM user_modification');
        $this->addSql('DROP TABLE user_modification');
        $this->addSql('CREATE TABLE user_modification (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id_id INTEGER DEFAULT NULL, modification_id_id INTEGER DEFAULT NULL, CONSTRAINT FK_6FA859D09D86650F FOREIGN KEY (user_id_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_6FA859D0B5519852 FOREIGN KEY (modification_id_id) REFERENCES modification (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO user_modification (id, user_id_id, modification_id_id) SELECT id, user_id_id, modification_id_id FROM __temp__user_modification');
        $this->addSql('DROP TABLE __temp__user_modification');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6FA859D0B5519852 ON user_modification (modification_id_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6FA859D09D86650F ON user_modification (user_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE patient_circuit_resource (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, patient_id_id INTEGER DEFAULT NULL, circuit_id_id INTEGER DEFAULT NULL, resource_id_id INTEGER NOT NULL, start_date_time DATETIME NOT NULL, end_date_time DATETIME NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C327907254FFE465 ON patient_circuit_resource (resource_id_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C3279072BC21E890 ON patient_circuit_resource (circuit_id_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C3279072EA724598 ON patient_circuit_resource (patient_id_id)');
        $this->addSql('DROP TABLE patient_activity_resource');
        $this->addSql('DROP TABLE patient_activity_resource_patient');
        $this->addSql('DROP TABLE patient_activity_resource_activity');
        $this->addSql('DROP TABLE patient_activity_resource_resource');
        $this->addSql('DROP INDEX IDX_C7EB954DFC9F73');
        $this->addSql('DROP INDEX IDX_C7EB9541419CFFC');
        $this->addSql('CREATE TEMPORARY TABLE __temp__activity_activity AS SELECT activity_source, activity_target FROM activity_activity');
        $this->addSql('DROP TABLE activity_activity');
        $this->addSql('CREATE TABLE activity_activity (activity_source INTEGER NOT NULL, activity_target INTEGER NOT NULL, PRIMARY KEY(activity_source, activity_target))');
        $this->addSql('INSERT INTO activity_activity (activity_source, activity_target) SELECT activity_source, activity_target FROM __temp__activity_activity');
        $this->addSql('DROP TABLE __temp__activity_activity');
        $this->addSql('CREATE INDEX IDX_C7EB954DFC9F73 ON activity_activity (activity_source)');
        $this->addSql('CREATE INDEX IDX_C7EB9541419CFFC ON activity_activity (activity_target)');
        $this->addSql('DROP INDEX IDX_1377854681C06096');
        $this->addSql('DROP INDEX IDX_13778546CF2182C8');
        $this->addSql('CREATE TEMPORARY TABLE __temp__activity_circuit AS SELECT activity_id, circuit_id FROM activity_circuit');
        $this->addSql('DROP TABLE activity_circuit');
        $this->addSql('CREATE TABLE activity_circuit (activity_id INTEGER NOT NULL, circuit_id INTEGER NOT NULL, PRIMARY KEY(activity_id, circuit_id))');
        $this->addSql('INSERT INTO activity_circuit (activity_id, circuit_id) SELECT activity_id, circuit_id FROM __temp__activity_circuit');
        $this->addSql('DROP TABLE __temp__activity_circuit');
        $this->addSql('CREATE INDEX IDX_1377854681C06096 ON activity_circuit (activity_id)');
        $this->addSql('CREATE INDEX IDX_13778546CF2182C8 ON activity_circuit (circuit_id)');
        $this->addSql('DROP INDEX IDX_BC91F41698EC6B7B');
        $this->addSql('CREATE TEMPORARY TABLE __temp__resource AS SELECT id, resource_type_id, name FROM resource');
        $this->addSql('DROP TABLE resource');
        $this->addSql('CREATE TABLE resource (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, resource_type_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO resource (id, resource_type_id, name) SELECT id, resource_type_id, name FROM __temp__resource');
        $this->addSql('DROP TABLE __temp__resource');
        $this->addSql('CREATE INDEX IDX_BC91F41698EC6B7B ON resource (resource_type_id)');
        $this->addSql('DROP INDEX IDX_2867086098EC6B7B');
        $this->addSql('DROP INDEX IDX_2867086081C06096');
        $this->addSql('CREATE TEMPORARY TABLE __temp__resource_type_activity AS SELECT resource_type_id, activity_id FROM resource_type_activity');
        $this->addSql('DROP TABLE resource_type_activity');
        $this->addSql('CREATE TABLE resource_type_activity (resource_type_id INTEGER NOT NULL, activity_id INTEGER NOT NULL, PRIMARY KEY(resource_type_id, activity_id))');
        $this->addSql('INSERT INTO resource_type_activity (resource_type_id, activity_id) SELECT resource_type_id, activity_id FROM __temp__resource_type_activity');
        $this->addSql('DROP TABLE __temp__resource_type_activity');
        $this->addSql('CREATE INDEX IDX_2867086098EC6B7B ON resource_type_activity (resource_type_id)');
        $this->addSql('CREATE INDEX IDX_2867086081C06096 ON resource_type_activity (activity_id)');
        $this->addSql('DROP INDEX UNIQ_6FA859D09D86650F');
        $this->addSql('DROP INDEX UNIQ_6FA859D0B5519852');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user_modification AS SELECT id, user_id_id, modification_id_id FROM user_modification');
        $this->addSql('DROP TABLE user_modification');
        $this->addSql('CREATE TABLE user_modification (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id_id INTEGER DEFAULT NULL, modification_id_id INTEGER DEFAULT NULL)');
        $this->addSql('INSERT INTO user_modification (id, user_id_id, modification_id_id) SELECT id, user_id_id, modification_id_id FROM __temp__user_modification');
        $this->addSql('DROP TABLE __temp__user_modification');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6FA859D09D86650F ON user_modification (user_id_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6FA859D0B5519852 ON user_modification (modification_id_id)');
    }
}
