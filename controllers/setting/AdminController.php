<?php
/**
 * AdminController
 * @var $this ommu\member\controllers\setting\AdminController
 * @var $model ommu\member\models\MemberSetting
 *
 * AdminController implements the CRUD actions for MemberSetting model.
 * Reference start
 * TOC :
 *	Index
 *	Update
 *	View
 *	Delete
 *
 *	findModel
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 5 November 2018, 06:17 WIB
 * @link https://github.com/ommu/mod-member
 *
 */

namespace ommu\member\controllers\setting;

use Yii;
use yii\filters\VerbFilter;
use app\components\Controller;
use mdm\admin\components\AccessControl;
use ommu\member\models\MemberSetting;
use yii\data\ActiveDataProvider;

class AdminController extends Controller
{
	/**
	 * {@inheritdoc}
	 */
	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::className(),
			],
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'delete' => ['POST'],
				],
			],
		];
	}

	/**
	 * Displays a single MemberSetting model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionIndex()
	{
		// echo '<pre>';
		// print_r(Yii::$app->authManager->getRoles());
		// print_r(\ommu\users\models\Assignments::getRoles());
		// $manager = Yii::$app->authManager;
		// $item	= $manager->getRole('userAdmin');
		// print_r($manager->getAssignments(1));
		// print_r($manager->getRole('userAdmin'));
		//$manager->assign($item, 1);

		$model = MemberSetting::findOne(1);
		if($model == null)
			return $this->redirect(['update']);

		$this->view->title = Yii::t('app', 'Member Settings');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_index', [
			'model' => $model,
		]);
	}

	/**
	 * Updates an existing MemberSetting model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate()
	{
		$model = MemberSetting::findOne(1);
		if($model == null)
			$model = new MemberSetting(['id'=>1]);

		if(Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());
			// $postData = Yii::$app->request->post();
			// $model->load($postData);
			// $model->order = $postData['order'] ? $postData['order'] : 0;

			if($model->save()) {
				Yii::$app->session->setFlash('success', Yii::t('app', 'Member setting success updated.'));
				return $this->redirect(['index']);

			} else {
				if(Yii::$app->request->isAjax)
					return \yii\helpers\Json::encode(\app\components\widgets\ActiveForm::validate($model));
			}
		}

		$this->view->title = Yii::t('app', 'Member Settings');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_update', [
			'model' => $model,
		]);
	}

	/**
	 * Deletes an existing MemberSetting model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete()
	{
		$this->findModel(1)->delete();
		
		Yii::$app->session->setFlash('success', Yii::t('app', 'Member setting success deleted.'));
		return $this->redirect(['index']);
	}

	/**
	 * Finds the MemberSetting model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return MemberSetting the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if(($model = MemberSetting::findOne($id)) !== null)
			return $model;

		throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}
