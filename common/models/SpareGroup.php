<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "spare_group".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $created_on
 * @property string|null $modified_on
 *
 * @property SpareGroupSpare[] $spareGroupSpares
 */
class SpareGroup extends \yii\db\ActiveRecord
{
	/**
	 * {@inheritdoc}
	 */
	public static function tableName()
	{
		return 'spare_group';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['created_on', 'modified_on'], 'safe'],
			[['name'], 'string', 'max' => 255],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'name' => 'Name',
			'created_on' => 'Created On',
			'modified_on' => 'Modified On',
		];
	}

	/**
	 * Gets query for [[SpareGroupSpares]].
	 *
	 * @return \yii\db\ActiveQuery
	 */
	public function getSpareGroupSpares()
	{
		return $this->hasMany(SpareGroupSpare::className(), ['spare_group_id' => 'id']);
	}
}
