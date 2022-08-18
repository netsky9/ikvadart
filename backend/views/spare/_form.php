<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Spare */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="spare-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'weight')->textInput() ?>

	<? 	$items = \yii\helpers\ArrayHelper::map($manufacturer,'id','name');
		echo $form->field($model, 'manufacturer_id')->dropDownList($items);?>

	<?	$options = [];
		$spareItems = \yii\helpers\ArrayHelper::map($spareGroup,'id','name');

		if (isset($spareGroupSpare)) {
			foreach ($spareGroupSpare as $spareGroupSpareItem){
				$options[$spareGroupSpareItem['spare_group_id']] = ['selected' => true];
			}
		}

		echo $form->field($modelSpareGroupSpare, 'spare_group_id')
			->dropDownList($spareItems, [
				'multiple' => 'multiple',
				'options' => $options
			]); ?>

    <?= $form->field($model, 'cost')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
