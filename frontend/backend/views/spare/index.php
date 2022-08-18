<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SpareSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Spares';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="spare-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Spare', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'weight',
            'manufacturer_id',
            'cost',
            //'created_on',
            //'modified_on',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Spare $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
