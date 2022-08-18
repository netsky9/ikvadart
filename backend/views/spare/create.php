<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Spare */

$this->title = 'Create Spare';
$this->params['breadcrumbs'][] = ['label' => 'Spares', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="spare-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'manufacturer' => $manufacturer,
		'spareGroup' => $spareGroup,
		'modelSpareGroupSpare' => $modelSpareGroupSpare
    ]) ?>

</div>
