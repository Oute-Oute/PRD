<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220621075206 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_FE38F8446B899279');
        $this->addSql('DROP INDEX IDX_FE38F844CF2182C8');
        $this->addSql('CREATE TEMPORARY TABLE __temp__appointment AS SELECT id, circuit_id, patient_id FROM appointment');
        $this->addSql('DROP TABLE appointment');
        $this->addSql('CREATE TABLE appointment (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, circuit_id INTEGER DEFAULT NULL, patient_id INTEGER DEFAULT NULL, CONSTRAINT FK_FE38F844CF2182C8 FOREIGN KEY (circuit_id) REFERENCES circuit (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_FE38F8446B899279 FOREIGN KEY (patient_id) REFERENCES patient (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO appointment (id, circuit_id, patient_id) SELECT id, circuit_id, patient_id FROM __temp__appointment');
        $this->addSql('DROP TABLE __temp__appointment');
        $this->addSql('CREATE INDEX IDX_FE38F8446B899279 ON appointment (patient_id)');
        $this->addSql('CREATE INDEX IDX_FE38F844CF2182C8 ON appointment (circuit_id)');
        $this->addSql('DROP INDEX IDX_FA7D1DC689329D25');
        $this->addSql('DROP INDEX IDX_FA7D1DC671F7E88B');
        $this->addSql('CREATE TEMPORARY TABLE __temp__event_resource AS SELECT event_id, resource_id FROM event_resource');
        $this->addSql('DROP TABLE event_resource');
        $this->addSql('CREATE TABLE event_resource (event_id INTEGER NOT NULL, resource_id INTEGER NOT NULL, PRIMARY KEY(event_id, resource_id), CONSTRAINT FK_FA7D1DC671F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_FA7D1DC689329D25 FOREIGN KEY (resource_id) REFERENCES resource (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO event_resource (event_id, resource_id) SELECT event_id, resource_id FROM __temp__event_resource');
        $this->addSql('DROP TABLE __temp__event_resource');
        $this->addSql('CREATE INDEX IDX_FA7D1DC689329D25 ON event_resource (resource_id)');
        $this->addSql('CREATE INDEX IDX_FA7D1DC671F7E88B ON event_resource (event_id)');
        $this->addSql('DROP INDEX IDX_96725A09CF2182C8');
        $this->addSql('DROP INDEX IDX_96725A0971F7E88B');
        $this->addSql('CREATE TEMPORARY TABLE __temp__event_circuit AS SELECT event_id, circuit_id FROM event_circuit');
        $this->addSql('DROP TABLE event_circuit');
        $this->addSql('CREATE TABLE event_circuit (event_id INTEGER NOT NULL, circuit_id INTEGER NOT NULL, PRIMARY KEY(event_id, circuit_id), CONSTRAINT FK_96725A0971F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_96725A09CF2182C8 FOREIGN KEY (circuit_id) REFERENCES circuit (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO event_circuit (event_id, circuit_id) SELECT event_id, circuit_id FROM __temp__event_circuit');
        $this->addSql('DROP TABLE __temp__event_circuit');
        $this->addSql('CREATE INDEX IDX_96725A09CF2182C8 ON event_circuit (circuit_id)');
        $this->addSql('CREATE INDEX IDX_96725A0971F7E88B ON event_circuit (event_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_FE38F844CF2182C8');
        $this->addSql('DROP INDEX IDX_FE38F8446B899279');
        $this->addSql('CREATE TEMPORARY TABLE __temp__appointment AS SELECT id, circuit_id, patient_id FROM appointment');
        $this->addSql('DROP TABLE appointment');
        $this->addSql('CREATE TABLE appointment (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, circuit_id INTEGER DEFAULT NULL, patient_id INTEGER DEFAULT NULL)');
        $this->addSql('INSERT INTO appointment (id, circuit_id, patient_id) SELECT id, circuit_id, patient_id FROM __temp__appointment');
        $this->addSql('DROP TABLE __temp__appointment');
        $this->addSql('CREATE INDEX IDX_FE38F844CF2182C8 ON appointment (circuit_id)');
        $this->addSql('CREATE INDEX IDX_FE38F8446B899279 ON appointment (patient_id)');
        $this->addSql('DROP INDEX IDX_96725A0971F7E88B');
        $this->addSql('DROP INDEX IDX_96725A09CF2182C8');
        $this->addSql('CREATE TEMPORARY TABLE __temp__event_circuit AS SELECT event_id, circuit_id FROM event_circuit');
        $this->addSql('DROP TABLE event_circuit');
        $this->addSql('CREATE TABLE event_circuit (event_id INTEGER NOT NULL, circuit_id INTEGER NOT NULL, PRIMARY KEY(event_id, circuit_id))');
        $this->addSql('INSERT INTO event_circuit (event_id, circuit_id) SELECT event_id, circuit_id FROM __temp__event_circuit');
        $this->addSql('DROP TABLE __temp__event_circuit');
        $this->addSql('CREATE INDEX IDX_96725A0971F7E88B ON event_circuit (event_id)');
        $this->addSql('CREATE INDEX IDX_96725A09CF2182C8 ON event_circuit (circuit_id)');
        $this->addSql('DROP INDEX IDX_FA7D1DC671F7E88B');
        $this->addSql('DROP INDEX IDX_FA7D1DC689329D25');
        $this->addSql('CREATE TEMPORARY TABLE __temp__event_resource AS SELECT event_id, resource_id FROM event_resource');
        $this->addSql('DROP TABLE event_resource');
        $this->addSql('CREATE TABLE event_resource (event_id INTEGER NOT NULL, resource_id INTEGER NOT NULL, PRIMARY KEY(event_id, resource_id))');
        $this->addSql('INSERT INTO event_resource (event_id, resource_id) SELECT event_id, resource_id FROM __temp__event_resource');
        $this->addSql('DROP TABLE __temp__event_resource');
        $this->addSql('CREATE INDEX IDX_FA7D1DC671F7E88B ON event_resource (event_id)');
        $this->addSql('CREATE INDEX IDX_FA7D1DC689329D25 ON event_resource (resource_id)');
    }
}
