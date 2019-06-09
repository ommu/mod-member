<?php
/**
 * ProfileDocumentController
 * @var $this ommu\member\controllers\setting\ProfileDocumentController
 * @var $model ommu\member\models\MemberProfileDocument
 *
 * ProfileDocumentController implements the CRUD actions for MemberProfileDocument model.
 * Reference start
 * TOC :
 *	Index
 *	Create
 *	Update
 *	View
 *	Delete
 *	RunAction
 *	Publish
 *
 *	findModel
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 2 October 2018, 11:36 WIB
 * @modified date 30 October 2018, 11:26 WIB
 * @link https://github.com/ommu/mod-member
 *
 */

namespace ommu\member\controllers\setting;

use Yii;
use yii\filters\VerbFilter;
use app\components\Controller;
use mdm\admin\components\AccessControl;
use ommu\member\models\MemberProfileDocument;
use ommu\member\models\search\MemberProfileDocument as MemberProfileDocumentSearch;

class ProfileDocumentController extends Controller
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
					'publish' => ['POST'],
				],
			],
		];
	}

	/**
	 * Lists all MemberProfileDocument models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new MemberProfileDocumentSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		$gridColumn = Yii::$app->request->get('GridColumn', null);
		$cols = [];
		if($gridColumn != null && count($gridColumn) > 0) {
			foreach($gridColumn as $key => $val) {
				if($gridColumn[$key] == 1)
					$cols[] = $key;
			}
		}
		$columns = $searchModel->getGridColumn($cols);

		$this->view->title = Yii::t('app', 'Profile Documents');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'columns' => $columns,
		]);
	}

	/**
	 * Creates a new MemberProfileDocument model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$profile = Yii::$app->request->get('profile');
		if(!$profile)
			throw new \yii\web\NotAcceptableHttpException(Yii::t('app', 'The requested page does not exist.'));

		$model = new MemberProfileDocument();

		if(Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());
			$model->profile_id = $profile;
			
			if($model->save()) {
				Yii::$app->session->setFlash('success', Yii::t('app', 'Member profile document success created.'));
				return $this->redirect(['index', 'profile'=>$model->profile_id]);
				//return $this->redirect(['view', 'id'=>$model->id]);

			} else {
				if(Yii::$app->request->isAjax)
					return \yii\helpers\Json::encode(\app\components\widgets\ActiveForm::validate($model));
			}
		}

		$this->view->title = Yii::t('app', 'Create Profile Document');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_create', [
			'model' => $model,
		]);
	}

	/**
	 * Updates an existing MemberProfileDocument model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		$model = $this->findModel($id);

		if(Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());
			// $postData = Yii::$app->request->post();
			// $model->load($postData);

			if($model->save()) {
				Yii::$app->session->setFlash('success', Yii::t('app', 'Member profile document success updated.'));
				return $this->redirect(['index', 'profile'=>$model->profile_id]);
				//return $this->redirect(['view', 'id'=>$model->id]);

			} else {
				if(Yii::$app->request->isAjax)
					return \yii\helpers\Json::encode(\app\components\widgets\ActiveForm::validate($model));
			}
		}

		$this->view->title = Yii::t('app', 'Update {model-class}: {profile-id}', ['model-class' => 'Profile Document', 'profile-id' => $model->profile->title->message]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_update', [
			'model' => $model,
		]);
	}

	/**
	 * Displays a single MemberProfileDocument model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id)
	{
		$model = $this->findModel($id);

		$this->view->title = Yii::t('app', 'Detail {model-class}: {profile-id}', ['model-class' => 'Profile Document', 'profile-id' => $model->profile->title->message]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_view', [
			'model' => $model,
		]);
	}

	/**
	 * Deletes an existing MemberProfileDocument model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$model = $this->findModel($id);
		$model->publish = 2;

		if($model->save(false, ['publish','modified_id'])) {
			Yii::$app->session->setFlash('success', Yii::t('app', 'Member profile document success deleted.'));
			return $this->redirect(['index', 'profile'=>$model->profile_id]);
		}
	}

	/**
	 * actionPublish an existing MemberProfileDocument model.
	 * If publish is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionPublish($id)
	{
		$model = $this->findModel($id);
		$replace = $model->publish == 1 ? 0 : 1;
		$model->publish = $replace;

		if($model->save(false, ['publish','modified_id'])) {
			Yii::$app->session->setFlash('success', Yii::t('app', 'Member profile document success updated.'));
			return $this->redirect(['index', 'profile'=>$model->profile_id]);
		}
	}

	/**
	 * Finds the MemberProfileDocument model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return MemberProfileDocument the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if(($model = MemberProfileDocument::findOne($id)) !== null)
			return $model;

		throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}
