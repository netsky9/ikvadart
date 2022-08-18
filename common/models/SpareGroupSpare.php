<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "spare_group_spare".
 *
 * @property int $id
 * @property int $spare_group_id
 * @property int $spare_id
 * @property string|null $created_on
 * @property string|null $modified_on
 *
 * @property Spare $spare
 * @property SpareGroup $spareGroup
 */
class SpareGroupSpare extends \yii\db\ActiveRecord
{
	/**
	 * {@inheritdoc}
	 */
	public static function tableName()
	{
		return 'spare_group_spare';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['spare_group_id', 'spare_id'], 'required'],
			[['spare_group_id', 'spare_id'], 'integer'],
			[['created_on', 'modified_on'], 'safe'],
			[['spare_group_id'], 'exist', 'skipOnError' => true, 'targetClass' => SpareGroup::className(), 'targetAttribute' => ['spare_group_id' => 'id']],
			[['spare_id'], 'exist', 'skipOnError' => true, 'targetClass' => Spare::className(), 'targetAttribute' => ['spare_id' => 'id']],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'spare_group_id' => 'Spare Group',
			'spare_id' => 'Spare',
			'created_on' => 'Created On',
			'modified_on' => 'Modified On',
		];
	}

	/**
	 * Gets query for [[Spare]].
	 *
	 * @return \yii\db\ActiveQuery
	 */
	public function getSpare()
	{
		return $this->hasOne(Spare::className(), ['id' => 'spare_id']);
	}

	/**
	 * Gets query for [[SpareGroup]].
	 *
	 * @return \yii\db\ActiveQuery
	 */
	public function getSpareGroup()
	{
		return $this->hasOne(SpareGroup::className(), ['id' => 'spare_group_id']);
	}
}
