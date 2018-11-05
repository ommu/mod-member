<?php
/**
 * AdminController
 * @var $this yii\web\View
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
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 5 November 2018, 06:17 WIB
 * @modified date 5 November 2018, 06:17 WIB
 * @modified by Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @link https://github.com/ommu/mod-member
 *
 */
 
namespace ommu\member\controllers\setting;

use Yii;
use yii\filters\VerbFilter;
use app\components\Controller;
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
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'delete' => ['POST'],
				],
			],
		];
	}

	/**
	 * Lists all MemberSetting models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		return $this->redirect(['update']);
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
			$model = new MemberSetting();

		if(Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());

			if($model->save()) {
				Yii::$app->session->setFlash('success', Yii::t('app', 'Member setting success updated.'));
				return $this->redirect(['index']);
				//return $this->redirect(['view', 'id'=>$model->id]);
			}
		}

		$this->view->title = Yii::t('app', 'Update {model-class}', ['model-class' => 'Setting']);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_update', [
			'model' => $model,
		]);
	}

	/**
	 * Displays a single MemberSetting model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView()
	{
		$model = MemberSetting::findOne(1);
		if($model == null)
			return $this->redirect(['update']);

		$this->view->title = Yii::t('app', 'Detail {model-class}: {id}', ['model-class' => 'Setting', 'id' => $model->id]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_view', [
			'model' => $model,
		]);
	}

	/**
	 * Deletes an existing MemberSetting model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$this->findModel($id)->delete();
		
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