<?php

namespace frontend\controllers;

use common\models\Manufacturer;
use common\models\Spare;
use common\models\SpareGroup;
use common\models\SpareGroupSpare;
use frontend\models\ResendVerificationEmailForm;
use frontend\models\VerifyEmailForm;
use Yii;
use yii\base\InvalidArgumentException;
use yii\data\Pagination;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use yii\db\Query;

class SiteController extends Controller
{
	/**
	 * @return array
	 */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

	/**
	 * @return array
	 */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

	/**
	 * @return string
	 */
    public function actionIndex()
    {
		$manufacturer = Yii::$app->request->get('manufacturer');
		$weightFrom = Yii::$app->request->get('weightFrom');
		$weightTo = Yii::$app->request->get('weightTo');
		$spareGroupsReq = Yii::$app->request->get('spareGroups');
		$costFrom = Yii::$app->request->get('costFrom');
		$costTo = Yii::$app->request->get('costTo');

		$manufacturers = Manufacturer::find()->all();
		$spareGroups = SpareGroup::find()->all();

		$query = (new Query())
			->select(['spare.*',
				'GROUP_CONCAT(DISTINCT(spare_group.name) ) as spare_group_names',
			])
			->from('spare_group_spare');

		// Filter
		if (isset($manufacturer) && !empty($manufacturer)) {
			$query->andWhere(['spare.manufacturer_id' => $manufacturer]);
		}

		if (isset($weightFrom) && !empty($weightFrom)) {
			$query->andWhere(['>', 'spare.weight', $weightFrom]);
		}

		if (isset($weightTo) && !empty($weightTo)) {
			$query->andWhere(['<', 'spare.weight', $weightTo]);
		}

		if (isset($costFrom) && !empty($costFrom)) {
			$query->andWhere(['>', 'spare.cost', $costFrom]);
		}

		if (isset($costTo) && !empty($costTo)) {
			$query->andWhere(['<', 'spare.cost', $costTo]);
		}

		if (isset($spareGroupsReq) && !empty($spareGroupsReq)) {
			$query->andWhere(['IN', 'spare_group_spare.spare_group_id', $spareGroupsReq]);
		}

		$query
			->leftJoin('spare','spare_group_spare.spare_id = spare.id')
			->addGroupBy('spare_group_spare.spare_id')
			->leftJoin('spare_group','spare_group_spare.spare_group_id = spare_group.id');

		// Pagination
		$countQuery = clone $query;
		$countItems = $countQuery->count();
		$pages = new Pagination([
			'totalCount' => $countItems,
			'defaultPageSize' => 3,
		]);
		$spares = $query->offset($pages->offset)
			->limit($pages->limit)
			->all();

        return $this->render('index', compact('manufacturers', 'spareGroups', 'spares', 'pages'));
    }

	/**
	 * @return string|\yii\web\Response
	 */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';

        return $this->render('login', [
            'model' => $model,
        ]);
    }

	/**
	 * @return \yii\web\Response
	 */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

	/**
	 * @return string|\yii\web\Response
	 */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
        }

        return $this->render('contact', [
            'model' => $model,
        ]);
    }

	/**
	 * @return string
	 */
    public function actionAbout()
    {
        return $this->render('about');
    }

	/**
	 * @return string|\yii\web\Response
	 */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            Yii::$app->session->setFlash('success', 'Thank you for registration. Please check your inbox for verification email.');
            return $this->goHome();
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

	/**
	 * @return string|\yii\web\Response
	 */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            }

            Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

	/**
	 * @param $token
	 * @return string|\yii\web\Response
	 * @throws BadRequestHttpException
	 */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

	/**
	 * @param $token
	 * @return \yii\web\Response
	 * @throws BadRequestHttpException
	 */
    public function actionVerifyEmail($token)
    {
        try {
            $model = new VerifyEmailForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if (($user = $model->verifyEmail()) && Yii::$app->user->login($user)) {
            Yii::$app->session->setFlash('success', 'Your email has been confirmed!');
            return $this->goHome();
        }

        Yii::$app->session->setFlash('error', 'Sorry, we are unable to verify your account with provided token.');
        return $this->goHome();
    }

	/**
	 * @return string|\yii\web\Response
	 */
    public function actionResendVerificationEmail()
    {
        $model = new ResendVerificationEmailForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                return $this->goHome();
            }
            Yii::$app->session->setFlash('error', 'Sorry, we are unable to resend verification email for the provided email address.');
        }

        return $this->render('resendVerificationEmail', [
            'model' => $model
        ]);
    }
}
