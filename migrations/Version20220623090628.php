<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220623090628 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE circuit_type (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL)');
        $this->addSql('DROP TABLE circuit_event');
        $this->addSql('DROP INDEX IDX_13778546CF2182C8');
        $this->addSql('DROP INDEX IDX_1377854681C06096');
        $this->addSql('CREATE TEMPORARY TABLE __temp__activity_circuit AS SELECT activity_id, circuit_id FROM activity_circuit');
        $this->addSql('DROP TABLE activity_circuit');
        $this->addSql('CREATE TABLE activity_circuit (activity_id INTEGER NOT NULL, circuit_id INTEGER NOT NULL, PRIMARY KEY(activity_id, circuit_id), CONSTRAINT FK_1377854681C06096 FOREIGN KEY (activity_id) REFERENCES activity (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_13778546CF2182C8 FOREIGN KEY (circuit_id) REFERENCES circuit (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO activity_circuit (activity_id, circuit_id) SELECT activity_id, circuit_id FROM __temp__activity_circuit');
        $this->addSql('DROP TABLE __temp__activity_circuit');
        $this->addSql('CREATE INDEX IDX_13778546CF2182C8 ON activity_circuit (circuit_id)');
        $this->addSql('CREATE INDEX IDX_1377854681C06096 ON activity_circuit (activity_id)');
        $this->addSql('DROP INDEX UNIQ_822C3E5711505E26');
        $this->addSql('DROP INDEX UNIQ_822C3E576146A8E4');
        $this->addSql('CREATE TEMPORARY TABLE __temp__activity_resource_type AS SELECT id, activity_id_id, resource_type_id_id FROM activity_resource_type');
        $this->addSql('DROP TABLE activity_resource_type');
        $this->addSql('CREATE TABLE activity_resource_type (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, activity_id_id INTEGER DEFAULT NULL, resource_type_id_id INTEGER DEFAULT NULL, CONSTRAINT FK_822C3E576146A8E4 FOREIGN KEY (activity_id_id) REFERENCES activity (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_822C3E5711505E26 FOREIGN KEY (resource_type_id_id) REFERENCES resource_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO activity_resource_type (id, activity_id_id, resource_type_id_id) SELECT id, activity_id_id, resource_type_id_id FROM __temp__activity_resource_type');
        $this->addSql('DROP TABLE __temp__activity_resource_type');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_822C3E5711505E26 ON activity_resource_type (resource_type_id_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_822C3E576146A8E4 ON activity_resource_type (activity_id_id)');
        $this->addSql('DROP INDEX UNIQ_C327907254FFE465');
        $this->addSql('DROP INDEX UNIQ_C3279072BC21E890');
        $this->addSql('DROP INDEX UNIQ_C3279072EA724598');
        $this->addSql('CREATE TEMPORARY TABLE __temp__patient_circuit_resource AS SELECT id, patient_id_id, circuit_id_id, resource_id_id, start_date_time, end_date_time FROM patient_circuit_resource');
        $this->addSql('DROP TABLE patient_circuit_resource');
        $this->addSql('CREATE TABLE patient_circuit_resource (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, patient_id_id INTEGER DEFAULT NULL, circuit_id_id INTEGER DEFAULT NULL, resource_id_id INTEGER NOT NULL, start_date_time DATETIME NOT NULL, end_date_time DATETIME NOT NULL, CONSTRAINT FK_C3279072EA724598 FOREIGN KEY (patient_id_id) REFERENCES patient (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_C3279072BC21E890 FOREIGN KEY (circuit_id_id) REFERENCES circuit (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_C327907254FFE465 FOREIGN KEY (resource_id_id) REFERENCES resource (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO patient_circuit_resource (id, patient_id_id, circuit_id_id, resource_id_id, start_date_time, end_date_time) SELECT id, patient_id_id, circuit_id_id, resource_id_id, start_date_time, end_date_time FROM __temp__patient_circuit_resource');
        $this->addSql('DROP TABLE __temp__patient_circuit_resource');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C327907254FFE465 ON patient_circuit_resource (resource_id_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C3279072BC21E890 ON patient_circuit_resource (circuit_id_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C3279072EA724598 ON patient_circuit_resource (patient_id_id)');
        $this->addSql('DROP INDEX IDX_BC91F41698EC6B7B');
        $this->addSql('CREATE TEMPORARY TABLE __temp__resource AS SELECT id, resource_type_id, name FROM resource');
        $this->addSql('DROP TABLE resource');
        $this->addSql('CREATE TABLE resource (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, resource_type_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, CONSTRAINT FK_BC91F41698EC6B7B FOREIGN KEY (resource_type_id) REFERENCES resource_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO resource (id, resource_type_id, name) SELECT id, resource_type_id, name FROM __temp__resource');
        $this->addSql('DROP TABLE __temp__resource');
        $this->addSql('CREATE INDEX IDX_BC91F41698EC6B7B ON resource (resource_type_id)');
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
        $this->addSql('CREATE TABLE circuit_event (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, circuit_id_id INTEGER DEFAULT NULL, activity_id_id INTEGER DEFAULT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F8CB7C606146A8E4 ON circuit_event (activity_id_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F8CB7C60BC21E890 ON circuit_event (circuit_id_id)');
        $this->addSql('DROP TABLE circuit_type');
        $this->addSql('DROP INDEX IDX_1377854681C06096');
        $this->addSql('DROP INDEX IDX_13778546CF2182C8');
        $this->addSql('CREATE TEMPORARY TABLE __temp__activity_circuit AS SELECT activity_id, circuit_id FROM activity_circuit');
        $this->addSql('DROP TABLE activity_circuit');
        $this->addSql('CREATE TABLE activity_circuit (activity_id INTEGER NOT NULL, circuit_id INTEGER NOT NULL, PRIMARY KEY(activity_id, circuit_id))');
        $this->addSql('INSERT INTO activity_circuit (activity_id, circuit_id) SELECT activity_id, circuit_id FROM __temp__activity_circuit');
        $this->addSql('DROP TABLE __temp__activity_circuit');
        $this->addSql('CREATE INDEX IDX_1377854681C06096 ON activity_circuit (activity_id)');
        $this->addSql('CREATE INDEX IDX_13778546CF2182C8 ON activity_circuit (circuit_id)');
        $this->addSql('DROP INDEX UNIQ_822C3E576146A8E4');
        $this->addSql('DROP INDEX UNIQ_822C3E5711505E26');
        $this->addSql('CREATE TEMPORARY TABLE __temp__activity_resource_type AS SELECT id, activity_id_id, resource_type_id_id FROM activity_resource_type');
        $this->addSql('DROP TABLE activity_resource_type');
        $this->addSql('CREATE TABLE activity_resource_type (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, activity_id_id INTEGER DEFAULT NULL, resource_type_id_id INTEGER DEFAULT NULL)');
        $this->addSql('INSERT INTO activity_resource_type (id, activity_id_id, resource_type_id_id) SELECT id, activity_id_id, resource_type_id_id FROM __temp__activity_resource_type');
        $this->addSql('DROP TABLE __temp__activity_resource_type');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_822C3E576146A8E4 ON activity_resource_type (activity_id_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_822C3E5711505E26 ON activity_resource_type (resource_type_id_id)');
        $this->addSql('DROP INDEX UNIQ_C3279072EA724598');
        $this->addSql('DROP INDEX UNIQ_C3279072BC21E890');
        $this->addSql('DROP INDEX UNIQ_C327907254FFE465');
        $this->addSql('CREATE TEMPORARY TABLE __temp__patient_circuit_resource AS SELECT id, patient_id_id, circuit_id_id, resource_id_id, start_date_time, end_date_time FROM patient_circuit_resource');
        $this->addSql('DROP TABLE patient_circuit_resource');
        $this->addSql('CREATE TABLE patient_circuit_resource (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, patient_id_id INTEGER DEFAULT NULL, circuit_id_id INTEGER DEFAULT NULL, resource_id_id INTEGER NOT NULL, start_date_time DATETIME NOT NULL, end_date_time DATETIME NOT NULL)');
        $this->addSql('INSERT INTO patient_circuit_resource (id, patient_id_id, circuit_id_id, resource_id_id, start_date_time, end_date_time) SELECT id, patient_id_id, circuit_id_id, resource_id_id, start_date_time, end_date_time FROM __temp__patient_circuit_resource');
        $this->addSql('DROP TABLE __temp__patient_circuit_resource');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C3279072EA724598 ON patient_circuit_resource (patient_id_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C3279072BC21E890 ON patient_circuit_resource (circuit_id_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C327907254FFE465 ON patient_circuit_resource (resource_id_id)');
        $this->addSql('DROP INDEX IDX_BC91F41698EC6B7B');
        $this->addSql('CREATE TEMPORARY TABLE __temp__resource AS SELECT id, resource_type_id, name FROM resource');
        $this->addSql('DROP TABLE resource');
        $this->addSql('CREATE TABLE resource (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, resource_type_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO resource (id, resource_type_id, name) SELECT id, resource_type_id, name FROM __temp__resource');
        $this->addSql('DROP TABLE __temp__resource');
        $this->addSql('CREATE INDEX IDX_BC91F41698EC6B7B ON resource (resource_type_id)');
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
