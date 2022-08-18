<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "spare".
 *
 * @property int $id
 * @property string|null $name
 * @property int|null $weight
 * @property int $manufacturer_id
 * @property int|null $cost
 * @property string|null $created_on
 * @property string|null $modified_on
 *
 * @property Manufacturer $manufacturer
 * @property SpareGroupSpare[] $spareGroupSpares
 */
class Spare extends \yii\db\ActiveRecord
{
	/**
	 * {@inheritdoc}
	 */
	public static function tableName()
	{
		return 'spare';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['weight', 'manufacturer_id', 'cost'], 'integer'],
			[['manufacturer_id'], 'required'],
			[['created_on', 'modified_on'], 'safe'],
			[['name'], 'string', 'max' => 255],
			[['manufacturer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Manufacturer::className(), 'targetAttribute' => ['manufacturer_id' => 'id']],
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
			'weight' => 'Weight',
			'manufacturer_id' => 'Manufacturer',
			'cost' => 'Cost',
			'created_on' => 'Created On',
			'modified_on' => 'Modified On',
		];
	}

	/**
	 * Gets query for [[Manufacturer]].
	 *
	 * @return \yii\db\ActiveQuery
	 */
	public function getManufacturer()
	{
		return $this->hasOne(Manufacturer::className(), ['id' => 'manufacturer_id']);
	}

	/**
	 * Gets query for [[SpareGroupSpares]].
	 *
	 * @return \yii\db\ActiveQuery
	 */
	public function getSpareGroupSpares()
	{
		return $this->hasMany(SpareGroupSpare::className(), ['spare_id' => 'id']);
	}
}
