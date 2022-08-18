<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "manufacturer".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $created_on
 * @property string|null $modified_on
 *
 * @property Spare[] $spares
 */
class Manufacturer extends \yii\db\ActiveRecord
{
	/**
	 * {@inheritdoc}
	 */
	public static function tableName()
	{
		return 'manufacturer';
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
	 * Gets query for [[Spares]].
	 *
	 * @return \yii\db\ActiveQuery
	 */
	public function getSpares()
	{
		return $this->hasMany(Spare::className(), ['manufacturer_id' => 'id']);
	}
}
