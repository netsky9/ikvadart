<?php

namespace backend\controllers;

use common\models\Manufacturer;
use common\models\Spare;
use common\models\SpareGroup;
use common\models\SpareGroupSpare;
use common\models\SpareSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

class SpareController extends Controller
{
	/**
	 * @return array
	 */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
				'access' => [
					'class' => AccessControl::className(),
					'rules' => [
						[
							'actions' => ['index', 'view', 'create', 'update', 'delete'],
							'allow' => true,
							'roles' => ['@'],
						],
					],
				],
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

	/**
	 * @return string
	 */
    public function actionIndex()
    {
        $searchModel = new SpareSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

	/**
	 * @param $id
	 * @return string
	 * @throws NotFoundHttpException
	 */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

	/**
	 * @return string|\yii\web\Response
	 * @throws \yii\db\Exception
	 */
    public function actionCreate()
    {
    	$manufacturer = Manufacturer::find()->all();
    	$spareGroup = SpareGroup::find()->all();
        $model = new Spare();
        $modelSpareGroupSpare = new SpareGroupSpare();

        if ($this->request->isPost) {
			$db = \Yii::$app->db;
			$transaction = $db->beginTransaction();

            if ($model->load($this->request->post()) && $model->save()) {
				$spareGroups = $this->request->post('SpareGroupSpare')['spare_group_id'];

				foreach ($spareGroups as $spareGroup){
					$modelSpareGroupSpare = new SpareGroupSpare();
					$modelSpareGroupSpare->spare_group_id = $spareGroup;
					$modelSpareGroupSpare->spare_id = $model->id;
					$modelSpareGroupSpare->save();
				}
            }

			$transaction->commit();

			return $this->redirect(['view', 'id' => $model->id]);
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', compact('model', 'manufacturer', 'spareGroup', 'modelSpareGroupSpare'));
    }

	/**
	 * @param $id
	 * @return string|\yii\web\Response
	 * @throws NotFoundHttpException
	 * @throws \yii\db\Exception
	 */
    public function actionUpdate($id)
    {
		$manufacturer = Manufacturer::find()->all();
		$spareGroup = SpareGroup::find()->all();
		$spareGroupSpare = SpareGroupSpare::find()->where(['spare_id' => $id])->all();
		$modelSpareGroupSpare = new SpareGroupSpare();
        $model = $this->findModel($id);

        if ($this->request->isPost) {
			$db = \Yii::$app->db;
			$transaction = $db->beginTransaction();

        	if($model->load($this->request->post()) && $model->save()) {
				SpareGroupSpare::deleteAll(['spare_id' => $model->id]);

				$spareGroups = $this->request->post('SpareGroupSpare')['spare_group_id'];

				foreach ($spareGroups as $spareGroup) {
					$modelSpareGroupSpare = new SpareGroupSpare();
					$modelSpareGroupSpare->spare_group_id = $spareGroup;
					$modelSpareGroupSpare->spare_id = $model->id;
					$modelSpareGroupSpare->save();
				}
			}

			$transaction->commit();

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', compact('model', 'manufacturer', 'spareGroup', 'modelSpareGroupSpare', 'spareGroupSpare'));
    }

	/**
	 * @param $id
	 * @return \yii\web\Response
	 * @throws NotFoundHttpException
	 * @throws \yii\db\StaleObjectException
	 */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

	/**
	 * @param $id
	 * @return Spare|null
	 * @throws NotFoundHttpException
	 */
    protected function findModel($id)
    {
        if (($model = Spare::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
