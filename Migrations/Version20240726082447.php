<?php

/*
 * This file is part of the "Shared Project Timesheets Bundle" for Kimai.
 * All rights reserved by Fabian Vetter (https://vettersolutions.de).
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace KimaiPlugin\SharedProjectTimesheetsBundle\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240726082447 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add nullable customer_id column and make project_id nullable';
    }

    public function up(Schema $schema): void
    {
        $tableName = Version2020120600000::SHARED_PROJECT_TIMESHEETS_TABLE_NAME;

        // raw sql needed because column position is not supported
        $this->addSql(sprintf('ALTER TABLE %s ADD customer_id INT(11) DEFAULT NULL AFTER id', $tableName));
        $this->addSql(sprintf('ALTER TABLE %s MODIFY project_id INT(11) DEFAULT NULL', $tableName));
        $this->addSql(sprintf('ALTER TABLE %s DROP INDEX UNIQ_BE51C9A166D1F9CF06F2E59', $tableName));
        $this->addSql(sprintf('ALTER TABLE %s ADD CONSTRAINT UNIQ_customer_id_project_id_share_key UNIQUE (customer_id, project_id, share_key)', $tableName));
        $this->addSql(sprintf('
            ALTER TABLE %s ADD CONSTRAINT fk_customer
            FOREIGN KEY (customer_id)
            REFERENCES kimai2_customers (id)
            ON DELETE CASCADE
            ON UPDATE CASCADE
        ', $tableName));
    }

    public function down(Schema $schema): void
    {
        $table = $schema->getTable(Version2020120600000::SHARED_PROJECT_TIMESHEETS_TABLE_NAME);
        $table->dropIndex('UNIQ_customer_id_project_id_share_key');
        $table->addUniqueIndex(['project_id', 'share_key']);
        $table->removeForeignKey('fk_customer');
        $table->dropColumn('customer_id');
        $table->modifyColumn('project_id', ['notnull' => true]);
    }
}
