<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220627092948 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE achr (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, activity_id INTEGER NOT NULL, categoryhumanresource_id INTEGER NOT NULL)');
        $this->addSql('CREATE INDEX IDX_FB60CBBC81C06096 ON achr (activity_id)');
        $this->addSql('CREATE INDEX IDX_FB60CBBC7DF4E972 ON achr (categoryhumanresource_id)');
        $this->addSql('CREATE TABLE acmr (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, activity_id INTEGER NOT NULL, categorymaterialresource_id INTEGER NOT NULL)');
        $this->addSql('CREATE INDEX IDX_86173FF981C06096 ON acmr (activity_id)');
        $this->addSql('CREATE INDEX IDX_86173FF97DF85AB ON acmr (categorymaterialresource_id)');
        $this->addSql('CREATE TABLE activity (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, activityname VARCHAR(255) NOT NULL, duration INTEGER NOT NULL)');
        $this->addSql('CREATE TABLE ap (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, pathway_id INTEGER NOT NULL, activity_id INTEGER NOT NULL, activityorder INTEGER NOT NULL, delayminafter INTEGER NOT NULL, delaymaxafter INTEGER NOT NULL)');
        $this->addSql('CREATE INDEX IDX_6D3A3925F3DA7551 ON ap (pathway_id)');
        $this->addSql('CREATE INDEX IDX_6D3A392581C06096 ON ap (activity_id)');
        $this->addSql('CREATE TABLE category_human_resource (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, category VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE category_material_resource (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, category VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE chr (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, humanresource_id INTEGER NOT NULL, categoryhumanresource_id INTEGER NOT NULL)');
        $this->addSql('CREATE INDEX IDX_A6FF5DD458A652CC ON chr (humanresource_id)');
        $this->addSql('CREATE INDEX IDX_A6FF5DD47DF4E972 ON chr (categoryhumanresource_id)');
        $this->addSql('CREATE TABLE hri (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, humanresource_id INTEGER NOT NULL, indisponibility_id INTEGER NOT NULL)');
        $this->addSql('CREATE INDEX IDX_90E2810258A652CC ON hri (humanresource_id)');
        $this->addSql('CREATE INDEX IDX_90E2810297FB7789 ON hri (indisponibility_id)');
        $this->addSql('CREATE TABLE hrsa (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, humanresource_id INTEGER NOT NULL, scheduledactivity_id INTEGER NOT NULL)');
        $this->addSql('CREATE INDEX IDX_B604C73558A652CC ON hrsa (humanresource_id)');
        $this->addSql('CREATE INDEX IDX_B604C7357CF91857 ON hrsa (scheduledactivity_id)');
        $this->addSql('CREATE TABLE human_resource (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, humanresourcename VARCHAR(255) NOT NULL, available BOOLEAN NOT NULL)');
        $this->addSql('CREATE TABLE imr (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, materialresource_id INTEGER NOT NULL, indisponibility_id INTEGER NOT NULL)');
        $this->addSql('CREATE INDEX IDX_D61F2C474A4B113F ON imr (materialresource_id)');
        $this->addSql('CREATE INDEX IDX_D61F2C4797FB7789 ON imr (indisponibility_id)');
        $this->addSql('CREATE TABLE indisponibilities (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, startdatetime DATETIME NOT NULL, enddatetime DATETIME NOT NULL)');
        $this->addSql('CREATE TABLE material_resource (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, categorymaterialresource_id INTEGER NOT NULL, materialresourcename VARCHAR(255) NOT NULL, available BOOLEAN NOT NULL)');
        $this->addSql('CREATE INDEX IDX_FA49D0B47DF85AB ON material_resource (categorymaterialresource_id)');
        $this->addSql('CREATE TABLE modification (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, datemodif DATE NOT NULL, modified BOOLEAN NOT NULL)');
        $this->addSql('CREATE INDEX IDX_EF6425D2A76ED395 ON modification (user_id)');
        $this->addSql('CREATE TABLE mrsa (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, materialresource_id INTEGER NOT NULL, scheduledactivity_id INTEGER NOT NULL)');
        $this->addSql('CREATE INDEX IDX_81DA37074A4B113F ON mrsa (materialresource_id)');
        $this->addSql('CREATE INDEX IDX_81DA37077CF91857 ON mrsa (scheduledactivity_id)');
        $this->addSql('CREATE TABLE pathway (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, target INTEGER DEFAULT NULL, pathwayname VARCHAR(255) NOT NULL, pathwaytype VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE patient (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, lastname VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE pp (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, patient_id INTEGER NOT NULL, pathway_id INTEGER NOT NULL)');
        $this->addSql('CREATE INDEX IDX_3EE31A356B899279 ON pp (patient_id)');
        $this->addSql('CREATE INDEX IDX_3EE31A35F3DA7551 ON pp (pathway_id)');
        $this->addSql('CREATE TABLE scheduled_activity (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, activity_id INTEGER NOT NULL, patient_id INTEGER NOT NULL, startdatetime DATETIME NOT NULL, enddatetime DATETIME NOT NULL)');
        $this->addSql('CREATE INDEX IDX_DDA14B8581C06096 ON scheduled_activity (activity_id)');
        $this->addSql('CREATE INDEX IDX_DDA14B856B899279 ON scheduled_activity (patient_id)');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON user (username)');
        $this->addSql('CREATE TABLE working_hours (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, workinghours_id INTEGER NOT NULL, startdatetime DATETIME NOT NULL, enddatetime DATETIME NOT NULL)');
        $this->addSql('CREATE INDEX IDX_D72CDC3D899D27DB ON working_hours (workinghours_id)');
        $this->addSql('CREATE TABLE messenger_messages (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, body CLOB NOT NULL, headers CLOB NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL)');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE achr');
        $this->addSql('DROP TABLE acmr');
        $this->addSql('DROP TABLE activity');
        $this->addSql('DROP TABLE ap');
        $this->addSql('DROP TABLE category_human_resource');
        $this->addSql('DROP TABLE category_material_resource');
        $this->addSql('DROP TABLE chr');
        $this->addSql('DROP TABLE hri');
        $this->addSql('DROP TABLE hrsa');
        $this->addSql('DROP TABLE human_resource');
        $this->addSql('DROP TABLE imr');
        $this->addSql('DROP TABLE indisponibilities');
        $this->addSql('DROP TABLE material_resource');
        $this->addSql('DROP TABLE modification');
        $this->addSql('DROP TABLE mrsa');
        $this->addSql('DROP TABLE pathway');
        $this->addSql('DROP TABLE patient');
        $this->addSql('DROP TABLE pp');
        $this->addSql('DROP TABLE scheduled_activity');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE working_hours');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
