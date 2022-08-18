<?php

namespace backend\controllers;

use common\models\SpareGroup;
use common\models\SpareGroupSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

class SpareGroupController extends Controller
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
        $searchModel = new SpareGroupSearch();
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
	 */
    public function actionCreate()
    {
        $model = new SpareGroup();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

	/**
	 * @param $id
	 * @return string|\yii\web\Response
	 * @throws NotFoundHttpException
	 */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
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
	 * @return SpareGroup|null
	 * @throws NotFoundHttpException
	 */
    protected function findModel($id)
    {
        if (($model = SpareGroup::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
