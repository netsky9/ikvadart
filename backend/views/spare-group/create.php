<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\SpareGroup */

$this->title = 'Create Spare Group';
$this->params['breadcrumbs'][] = ['label' => 'Spare Groups', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="spare-group-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
