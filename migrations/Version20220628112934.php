<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220628112934 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE activity_human_resource (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, activity_id INTEGER NOT NULL, humanresourcecategory_id INTEGER NOT NULL, quantity INTEGER NOT NULL)');
        $this->addSql('CREATE INDEX IDX_C93D462A81C06096 ON activity_human_resource (activity_id)');
        $this->addSql('CREATE INDEX IDX_C93D462A85BDF988 ON activity_human_resource (humanresourcecategory_id)');
        $this->addSql('CREATE TABLE activity_material_resource (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, activity_id INTEGER NOT NULL, materialresourcecategory_id INTEGER NOT NULL, quantity INTEGER NOT NULL)');
        $this->addSql('CREATE INDEX IDX_5911932181C06096 ON activity_material_resource (activity_id)');
        $this->addSql('CREATE INDEX IDX_591193213140497B ON activity_material_resource (materialresourcecategory_id)');
        $this->addSql('CREATE TABLE appointment (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, patient_id INTEGER NOT NULL, pathway_id INTEGER NOT NULL, earliestappointmenttime TIME DEFAULT NULL, latestappointmenttime TIME DEFAULT NULL, dayappointment DATE NOT NULL, scheduled BOOLEAN NOT NULL)');
        $this->addSql('CREATE INDEX IDX_FE38F8446B899279 ON appointment (patient_id)');
        $this->addSql('CREATE INDEX IDX_FE38F844F3DA7551 ON appointment (pathway_id)');
        $this->addSql('CREATE TABLE category_of_human_resource (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, humanresource_id INTEGER NOT NULL, humanresourcecategory_id INTEGER NOT NULL)');
        $this->addSql('CREATE INDEX IDX_A297C7A758A652CC ON category_of_human_resource (humanresource_id)');
        $this->addSql('CREATE INDEX IDX_A297C7A785BDF988 ON category_of_human_resource (humanresourcecategory_id)');
        $this->addSql('CREATE TABLE category_of_material_resource (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, materialresource_id INTEGER NOT NULL, materialresourcecategory_id INTEGER NOT NULL)');
        $this->addSql('CREATE INDEX IDX_A467F8654A4B113F ON category_of_material_resource (materialresource_id)');
        $this->addSql('CREATE INDEX IDX_A467F8653140497B ON category_of_material_resource (materialresourcecategory_id)');
        $this->addSql('CREATE TABLE human_resource_category (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, categoryname VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE human_resource_scheduled (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, humanresource_id INTEGER NOT NULL, scheduledactivity_id INTEGER NOT NULL)');
        $this->addSql('CREATE INDEX IDX_2E4F9E8C58A652CC ON human_resource_scheduled (humanresource_id)');
        $this->addSql('CREATE INDEX IDX_2E4F9E8C7CF91857 ON human_resource_scheduled (scheduledactivity_id)');
        $this->addSql('CREATE TABLE indisponibilities_human_resource (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, humanresource_id INTEGER NOT NULL, indisponibilities_id INTEGER NOT NULL)');
        $this->addSql('CREATE INDEX IDX_5F1EFDA258A652CC ON indisponibilities_human_resource (humanresource_id)');
        $this->addSql('CREATE INDEX IDX_5F1EFDA264C42800 ON indisponibilities_human_resource (indisponibilities_id)');
        $this->addSql('CREATE TABLE indisponibilities_material_resource (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, materialresource_id INTEGER NOT NULL, indisponibilities_id INTEGER NOT NULL)');
        $this->addSql('CREATE INDEX IDX_1361DF8E4A4B113F ON indisponibilities_material_resource (materialresource_id)');
        $this->addSql('CREATE INDEX IDX_1361DF8E64C42800 ON indisponibilities_material_resource (indisponibilities_id)');
        $this->addSql('CREATE TABLE material_resource_category (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, categoryname VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE material_resource_scheduled (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, materialresource_id INTEGER NOT NULL, scheduledactivity_id INTEGER NOT NULL)');
        $this->addSql('CREATE INDEX IDX_9AB22E7F4A4B113F ON material_resource_scheduled (materialresource_id)');
        $this->addSql('CREATE INDEX IDX_9AB22E7F7CF91857 ON material_resource_scheduled (scheduledactivity_id)');
        $this->addSql('CREATE TABLE settings (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, timer INTEGER NOT NULL, unittime INTEGER NOT NULL)');
        $this->addSql('CREATE TABLE successor (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, activitya_id INTEGER NOT NULL, activityb_id INTEGER NOT NULL, delaymin INTEGER DEFAULT NULL, delaymax INTEGER DEFAULT NULL)');
        $this->addSql('CREATE INDEX IDX_DD2819F848CD420 ON successor (activitya_id)');
        $this->addSql('CREATE INDEX IDX_DD2819F816397BCE ON successor (activityb_id)');
        $this->addSql('CREATE TABLE target (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, pathway_id INTEGER NOT NULL, target INTEGER NOT NULL, dayweek INTEGER NOT NULL)');
        $this->addSql('CREATE INDEX IDX_466F2FFCF3DA7551 ON target (pathway_id)');
        $this->addSql('DROP TABLE achr');
        $this->addSql('DROP TABLE acmr');
        $this->addSql('DROP TABLE ap');
        $this->addSql('DROP TABLE category_human_resource');
        $this->addSql('DROP TABLE category_material_resource');
        $this->addSql('DROP TABLE chr');
        $this->addSql('DROP TABLE hri');
        $this->addSql('DROP TABLE hrsa');
        $this->addSql('DROP TABLE imr');
        $this->addSql('DROP TABLE mrsa');
        $this->addSql('DROP TABLE pp');
        $this->addSql('CREATE TEMPORARY TABLE __temp__activity AS SELECT id, activityname, duration FROM activity');
        $this->addSql('DROP TABLE activity');
        $this->addSql('CREATE TABLE activity (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, pathway_id INTEGER NOT NULL, activityname VARCHAR(255) NOT NULL, duration INTEGER NOT NULL, CONSTRAINT FK_AC74095AF3DA7551 FOREIGN KEY (pathway_id) REFERENCES pathway (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO activity (id, activityname, duration) SELECT id, activityname, duration FROM __temp__activity');
        $this->addSql('DROP TABLE __temp__activity');
        $this->addSql('CREATE INDEX IDX_AC74095AF3DA7551 ON activity (pathway_id)');
        $this->addSql('DROP INDEX IDX_FA49D0B47DF85AB');
        $this->addSql('CREATE TEMPORARY TABLE __temp__material_resource AS SELECT id, materialresourcename, available FROM material_resource');
        $this->addSql('DROP TABLE material_resource');
        $this->addSql('CREATE TABLE material_resource (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, materialresourcename VARCHAR(255) NOT NULL, available BOOLEAN NOT NULL)');
        $this->addSql('INSERT INTO material_resource (id, materialresourcename, available) SELECT id, materialresourcename, available FROM __temp__material_resource');
        $this->addSql('DROP TABLE __temp__material_resource');
        $this->addSql('DROP INDEX IDX_EF6425D2A76ED395');
        $this->addSql('CREATE TEMPORARY TABLE __temp__modification AS SELECT id, user_id, datemodif FROM modification');
        $this->addSql('DROP TABLE modification');
        $this->addSql('CREATE TABLE modification (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, datemodified DATE NOT NULL, datetimemodification DATETIME NOT NULL, CONSTRAINT FK_EF6425D2A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO modification (id, user_id, datemodified) SELECT id, user_id, datemodif FROM __temp__modification');
        $this->addSql('DROP TABLE __temp__modification');
        $this->addSql('CREATE INDEX IDX_EF6425D2A76ED395 ON modification (user_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__pathway AS SELECT id, pathwayname FROM pathway');
        $this->addSql('DROP TABLE pathway');
        $this->addSql('CREATE TABLE pathway (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, pathwayname VARCHAR(255) NOT NULL, available BOOLEAN NOT NULL)');
        $this->addSql('INSERT INTO pathway (id, pathwayname) SELECT id, pathwayname FROM __temp__pathway');
        $this->addSql('DROP TABLE __temp__pathway');
        $this->addSql('DROP INDEX IDX_DDA14B8581C06096');
        $this->addSql('DROP INDEX IDX_DDA14B856B899279');
        $this->addSql('CREATE TEMPORARY TABLE __temp__scheduled_activity AS SELECT id, activity_id, patient_id FROM scheduled_activity');
        $this->addSql('DROP TABLE scheduled_activity');
        $this->addSql('CREATE TABLE scheduled_activity (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, activity_id INTEGER NOT NULL, patient_id INTEGER NOT NULL, starttime TIME NOT NULL, endtime TIME NOT NULL, dayscheduled DATE NOT NULL, CONSTRAINT FK_DDA14B8581C06096 FOREIGN KEY (activity_id) REFERENCES activity (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_DDA14B856B899279 FOREIGN KEY (patient_id) REFERENCES patient (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO scheduled_activity (id, activity_id, patient_id) SELECT id, activity_id, patient_id FROM __temp__scheduled_activity');
        $this->addSql('DROP TABLE __temp__scheduled_activity');
        $this->addSql('CREATE INDEX IDX_DDA14B8581C06096 ON scheduled_activity (activity_id)');
        $this->addSql('CREATE INDEX IDX_DDA14B856B899279 ON scheduled_activity (patient_id)');
        $this->addSql('DROP INDEX IDX_D72CDC3D58A652CC');
        $this->addSql('CREATE TEMPORARY TABLE __temp__working_hours AS SELECT id, humanresource_id FROM working_hours');
        $this->addSql('DROP TABLE working_hours');
        $this->addSql('CREATE TABLE working_hours (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, humanresource_id INTEGER NOT NULL, starttime TIME NOT NULL, endtime TIME NOT NULL, dayweek INTEGER NOT NULL, CONSTRAINT FK_D72CDC3D58A652CC FOREIGN KEY (humanresource_id) REFERENCES human_resource (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO working_hours (id, humanresource_id) SELECT id, humanresource_id FROM __temp__working_hours');
        $this->addSql('DROP TABLE __temp__working_hours');
        $this->addSql('CREATE INDEX IDX_D72CDC3D58A652CC ON working_hours (humanresource_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE achr (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, activity_id INTEGER NOT NULL, categoryhumanresource_id INTEGER NOT NULL)');
        $this->addSql('CREATE INDEX IDX_FB60CBBC81C06096 ON achr (activity_id)');
        $this->addSql('CREATE INDEX IDX_FB60CBBC7DF4E972 ON achr (categoryhumanresource_id)');
        $this->addSql('CREATE TABLE acmr (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, activity_id INTEGER NOT NULL, categorymaterialresource_id INTEGER NOT NULL)');
        $this->addSql('CREATE INDEX IDX_86173FF981C06096 ON acmr (activity_id)');
        $this->addSql('CREATE INDEX IDX_86173FF97DF85AB ON acmr (categorymaterialresource_id)');
        $this->addSql('CREATE TABLE ap (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, pathway_id INTEGER NOT NULL, activity_id INTEGER NOT NULL, activityorder INTEGER NOT NULL, delayminafter INTEGER NOT NULL, delaymaxafter INTEGER NOT NULL)');
        $this->addSql('CREATE INDEX IDX_6D3A3925F3DA7551 ON ap (pathway_id)');
        $this->addSql('CREATE INDEX IDX_6D3A392581C06096 ON ap (activity_id)');
        $this->addSql('CREATE TABLE category_human_resource (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, category VARCHAR(255) NOT NULL COLLATE BINARY)');
        $this->addSql('CREATE TABLE category_material_resource (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, category VARCHAR(255) NOT NULL COLLATE BINARY)');
        $this->addSql('CREATE TABLE chr (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, humanresource_id INTEGER NOT NULL, categoryhumanresource_id INTEGER NOT NULL)');
        $this->addSql('CREATE INDEX IDX_A6FF5DD458A652CC ON chr (humanresource_id)');
        $this->addSql('CREATE INDEX IDX_A6FF5DD47DF4E972 ON chr (categoryhumanresource_id)');
        $this->addSql('CREATE TABLE hri (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, humanresource_id INTEGER NOT NULL, indisponibility_id INTEGER NOT NULL)');
        $this->addSql('CREATE INDEX IDX_90E2810258A652CC ON hri (humanresource_id)');
        $this->addSql('CREATE INDEX IDX_90E2810297FB7789 ON hri (indisponibility_id)');
        $this->addSql('CREATE TABLE hrsa (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, humanresource_id INTEGER NOT NULL, scheduledactivity_id INTEGER NOT NULL)');
        $this->addSql('CREATE INDEX IDX_B604C73558A652CC ON hrsa (humanresource_id)');
        $this->addSql('CREATE INDEX IDX_B604C7357CF91857 ON hrsa (scheduledactivity_id)');
        $this->addSql('CREATE TABLE imr (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, materialresource_id INTEGER NOT NULL, indisponibility_id INTEGER NOT NULL)');
        $this->addSql('CREATE INDEX IDX_D61F2C474A4B113F ON imr (materialresource_id)');
        $this->addSql('CREATE INDEX IDX_D61F2C4797FB7789 ON imr (indisponibility_id)');
        $this->addSql('CREATE TABLE mrsa (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, materialresource_id INTEGER NOT NULL, scheduledactivity_id INTEGER NOT NULL)');
        $this->addSql('CREATE INDEX IDX_81DA37074A4B113F ON mrsa (materialresource_id)');
        $this->addSql('CREATE INDEX IDX_81DA37077CF91857 ON mrsa (scheduledactivity_id)');
        $this->addSql('CREATE TABLE pp (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, patient_id INTEGER NOT NULL, pathway_id INTEGER NOT NULL)');
        $this->addSql('CREATE INDEX IDX_3EE31A356B899279 ON pp (patient_id)');
        $this->addSql('CREATE INDEX IDX_3EE31A35F3DA7551 ON pp (pathway_id)');
        $this->addSql('DROP TABLE activity_human_resource');
        $this->addSql('DROP TABLE activity_material_resource');
        $this->addSql('DROP TABLE appointment');
        $this->addSql('DROP TABLE category_of_human_resource');
        $this->addSql('DROP TABLE category_of_material_resource');
        $this->addSql('DROP TABLE human_resource_category');
        $this->addSql('DROP TABLE human_resource_scheduled');
        $this->addSql('DROP TABLE indisponibilities_human_resource');
        $this->addSql('DROP TABLE indisponibilities_material_resource');
        $this->addSql('DROP TABLE material_resource_category');
        $this->addSql('DROP TABLE material_resource_scheduled');
        $this->addSql('DROP TABLE settings');
        $this->addSql('DROP TABLE successor');
        $this->addSql('DROP TABLE target');
        $this->addSql('DROP INDEX IDX_AC74095AF3DA7551');
        $this->addSql('CREATE TEMPORARY TABLE __temp__activity AS SELECT id, activityname, duration FROM activity');
        $this->addSql('DROP TABLE activity');
        $this->addSql('CREATE TABLE activity (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, activityname VARCHAR(255) NOT NULL, duration INTEGER NOT NULL)');
        $this->addSql('INSERT INTO activity (id, activityname, duration) SELECT id, activityname, duration FROM __temp__activity');
        $this->addSql('DROP TABLE __temp__activity');
        $this->addSql('CREATE TEMPORARY TABLE __temp__material_resource AS SELECT id, materialresourcename, available FROM material_resource');
        $this->addSql('DROP TABLE material_resource');
        $this->addSql('CREATE TABLE material_resource (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, materialresourcename VARCHAR(255) NOT NULL, available BOOLEAN NOT NULL, categorymaterialresource_id INTEGER NOT NULL)');
        $this->addSql('INSERT INTO material_resource (id, materialresourcename, available) SELECT id, materialresourcename, available FROM __temp__material_resource');
        $this->addSql('DROP TABLE __temp__material_resource');
        $this->addSql('CREATE INDEX IDX_FA49D0B47DF85AB ON material_resource (categorymaterialresource_id)');
        $this->addSql('DROP INDEX IDX_EF6425D2A76ED395');
        $this->addSql('CREATE TEMPORARY TABLE __temp__modification AS SELECT id, user_id, datemodified FROM modification');
        $this->addSql('DROP TABLE modification');
        $this->addSql('CREATE TABLE modification (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, datemodif DATE NOT NULL, modified BOOLEAN NOT NULL)');
        $this->addSql('INSERT INTO modification (id, user_id, datemodif) SELECT id, user_id, datemodified FROM __temp__modification');
        $this->addSql('DROP TABLE __temp__modification');
        $this->addSql('CREATE INDEX IDX_EF6425D2A76ED395 ON modification (user_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__pathway AS SELECT id, pathwayname FROM pathway');
        $this->addSql('DROP TABLE pathway');
        $this->addSql('CREATE TABLE pathway (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, pathwayname VARCHAR(255) NOT NULL, target INTEGER DEFAULT NULL, pathwaytype VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO pathway (id, pathwayname) SELECT id, pathwayname FROM __temp__pathway');
        $this->addSql('DROP TABLE __temp__pathway');
        $this->addSql('DROP INDEX IDX_DDA14B8581C06096');
        $this->addSql('DROP INDEX IDX_DDA14B856B899279');
        $this->addSql('CREATE TEMPORARY TABLE __temp__scheduled_activity AS SELECT id, activity_id, patient_id FROM scheduled_activity');
        $this->addSql('DROP TABLE scheduled_activity');
        $this->addSql('CREATE TABLE scheduled_activity (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, activity_id INTEGER NOT NULL, patient_id INTEGER NOT NULL, startdatetime DATETIME NOT NULL, enddatetime DATETIME NOT NULL)');
        $this->addSql('INSERT INTO scheduled_activity (id, activity_id, patient_id) SELECT id, activity_id, patient_id FROM __temp__scheduled_activity');
        $this->addSql('DROP TABLE __temp__scheduled_activity');
        $this->addSql('CREATE INDEX IDX_DDA14B8581C06096 ON scheduled_activity (activity_id)');
        $this->addSql('CREATE INDEX IDX_DDA14B856B899279 ON scheduled_activity (patient_id)');
        $this->addSql('DROP INDEX IDX_D72CDC3D58A652CC');
        $this->addSql('CREATE TEMPORARY TABLE __temp__working_hours AS SELECT id, humanresource_id FROM working_hours');
        $this->addSql('DROP TABLE working_hours');
        $this->addSql('CREATE TABLE working_hours (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, humanresource_id INTEGER NOT NULL, startdatetime DATETIME NOT NULL, enddatetime DATETIME NOT NULL)');
        $this->addSql('INSERT INTO working_hours (id, humanresource_id) SELECT id, humanresource_id FROM __temp__working_hours');
        $this->addSql('DROP TABLE __temp__working_hours');
        $this->addSql('CREATE INDEX IDX_D72CDC3D58A652CC ON working_hours (humanresource_id)');
    }
}
