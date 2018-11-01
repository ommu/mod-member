<?php
/**
 * ProfileCategoryController
 * @var $this yii\web\View
 * @var $model ommu\member\models\MemberProfileCategory
 *
 * ProfileCategoryController implements the CRUD actions for MemberProfileCategory model.
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
 * @created date 2 October 2018, 09:58 WIB
 * @modified date 28 October 2018, 21:38 WIB
 * @link https://github.com/ommu/mod-member
 *
 */
 
namespace ommu\member\controllers\setting;

use Yii;
use yii\filters\VerbFilter;
use app\components\Controller;
use ommu\member\models\MemberProfileCategory;
use ommu\member\models\search\MemberProfileCategory as MemberProfileCategorySearch;

class ProfileCategoryController extends Controller
{
	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
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
	 * Lists all MemberProfileCategory models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new MemberProfileCategorySearch();
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

		$this->view->title = Yii::t('app', 'Profile Categories');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'columns' => $columns,
		]);
	}

	/**
	 * Creates a new MemberProfileCategory model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$profile = Yii::$app->request->get('profile');
		if(!$profile)
			throw new \yii\web\NotAcceptableHttpException(Yii::t('app', 'The requested page does not exist.'));

		$model = new MemberProfileCategory();

		if(Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());
			$model->profile_id = $profile;

			if($model->save()) {
				Yii::$app->session->setFlash('success', Yii::t('app', 'Member profile category success created.'));
				return $this->redirect(['index']);
				//return $this->redirect(['view', 'id' => $model->cat_id]);
			} 
		}

		$this->view->title = Yii::t('app', 'Create Profile Category');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_create', [
			'model' => $model,
			'profile' => $profile,
		]);
	}

	/**
	 * Updates an existing MemberProfileCategory model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		$model = $this->findModel($id);
		if(Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());

			if($model->save()) {
				Yii::$app->session->setFlash('success', Yii::t('app', 'Member profile category success updated.'));
				return $this->redirect(['index']);
				//return $this->redirect(['view', 'id' => $model->cat_id]);
			}
		}

		$this->view->title = Yii::t('app', 'Update {model-class}: {cat-name}', ['model-class' => 'Profile Category', 'cat-name' => $model->title->message]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_update', [
			'model' => $model,
			'profile' => $model->profile_id,
		]);
	}

	/**
	 * Displays a single MemberProfileCategory model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id)
	{
		$model = $this->findModel($id);

		$this->view->title = Yii::t('app', 'Detail {model-class}: {cat-name}', ['model-class' => 'Profile Category', 'cat-name' => $model->title->message]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_view', [
			'model' => $model,
		]);
	}

	/**
	 * Deletes an existing MemberProfileCategory model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$model = $this->findModel($id);
		$model->publish = 2;

		if($model->save(false, ['publish'])) {
			Yii::$app->session->setFlash('success', Yii::t('app', 'Member profile category success deleted.'));
			return $this->redirect(['index']);
			//return $this->redirect(['view', 'id' => $model->cat_id]);
		}
	}

	/**
	 * actionPublish an existing MemberProfileCategory model.
	 * If publish is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionPublish($id)
	{
		$model = $this->findModel($id);
		$replace = $model->publish == 1 ? 0 : 1;
		$model->publish = $replace;

		if($model->save(false, ['publish'])) {
			Yii::$app->session->setFlash('success', Yii::t('app', 'Member profile category success updated.'));
			return $this->redirect(['index']);
		}
	}

	/**
	 * Finds the MemberProfileCategory model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return MemberProfileCategory the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if(($model = MemberProfileCategory::findOne($id)) !== null) 
			return $model;
		else
			throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}
