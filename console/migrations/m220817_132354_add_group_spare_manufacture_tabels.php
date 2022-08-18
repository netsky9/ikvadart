<?php

use yii\db\Migration;

/**
 * Class m220817_132354_add_group_spare_manufacture_tabels
 */
class m220817_132354_add_group_spare_manufacture_tabels extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			$tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
		}

		$this->createTable(
			'{{%manufacturer}}',
			[
				'id' => $this->primaryKey(),
				'name' => $this->string(),
				'created_on' => $this->timestamp()->defaultExpression("CURRENT_TIMESTAMP"),
				'modified_on' => $this->timestamp()->defaultExpression("CURRENT_TIMESTAMP")->append('ON UPDATE CURRENT_TIMESTAMP'),
			], $tableOptions
		);

		$this->createTable(
			'{{%spare_group}}',
			[
				'id' => $this->primaryKey(),
				'name' => $this->string(),
				'created_on' => $this->timestamp()->defaultExpression("CURRENT_TIMESTAMP"),
				'modified_on' => $this->timestamp()->defaultExpression("CURRENT_TIMESTAMP")->append('ON UPDATE CURRENT_TIMESTAMP'),
			], $tableOptions
		);

		$this->createTable(
			'{{%spare}}',
			[
				'id' => $this->primaryKey(),
				'name' => $this->string(),
				'weight' => $this->integer(),
				'manufacturer_id' => $this->integer()->notNull(),
				'cost' => $this->integer(),
				'created_on' => $this->timestamp()->defaultExpression("CURRENT_TIMESTAMP"),
				'modified_on' => $this->timestamp()->defaultExpression("CURRENT_TIMESTAMP")->append('ON UPDATE CURRENT_TIMESTAMP'),
			], $tableOptions
		);

		$this->createTable(
			'{{%spare_group_spare}}',
			[
				'id' => $this->primaryKey(),
				'spare_group_id' => $this->integer()->notNull(),
				'spare_id' => $this->integer()->notNull(),
				'created_on' => $this->timestamp()->defaultExpression("CURRENT_TIMESTAMP"),
				'modified_on' => $this->timestamp()->defaultExpression("CURRENT_TIMESTAMP")->append('ON UPDATE CURRENT_TIMESTAMP'),
			], $tableOptions
		);

		// add foreign key for table `review`
		$this->addForeignKey(
			'fk-spare-manufacturer_id',
			'spare',
			'manufacturer_id',
			'manufacturer',
			'id',
			'CASCADE'
		);

		// add foreign keys for table `spare_group_spare`
		$this->addForeignKey(
			'fk-spare_group_spare-spare_group_id',
			'spare_group_spare',
			'spare_group_id',
			'spare_group',
			'id',
			'CASCADE'
		);
		$this->addForeignKey(
			'fk-spare_group_spare-spare_id',
			'spare_group_spare',
			'spare_id',
			'spare',
			'id',
			'CASCADE'
		);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropForeignKey(
			'fk-spare_group_spare-spare_id',
			'spare_group_spare'
		);
		$this->dropForeignKey(
			'fk-spare_group_spare-spare_group_id',
			'spare_group_spare'
		);
		$this->dropForeignKey(
			'fk-spare-manufacturer_id',
			'spare'
		);
		$this->dropTable('{{%spare_group_spare}}');
		$this->dropTable('{{%spare}}');
		$this->dropTable('{{%spare_group}}');
		$this->dropTable('{{%manufacturer}}');
	}
}
