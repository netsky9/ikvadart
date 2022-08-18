<?php

use yii\helpers\Html;
use \yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Manufacturer */

$this->title = 'Create Manufacturer';
$this->params['breadcrumbs'][] = ['label' => 'Manufacturers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="manufacturer-create">

	<h1><?= Html::encode($this->title) ?></h1>

	<div class="manufacturer-form">

		<?php $form = ActiveForm::begin(); ?>

		<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

		<div class="form-group">
			<?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
		</div>

		<?php ActiveForm::end(); ?>

	</div>

</div>
