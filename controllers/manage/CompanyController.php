<?php
/**
 * CompanyController
 * @var $this yii\web\View
 * @var $model ommu\member\models\MemberCompany
 *
 * CompanyController implements the CRUD actions for MemberCompany model.
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
 * @created date 31 October 2018, 15:48 WIB
 * @link https://github.com/ommu/mod-member
 *
 */
 
namespace ommu\member\controllers\manage;

use Yii;
use yii\filters\VerbFilter;
use app\components\Controller;
use ommu\member\models\MemberCompany;
use ommu\member\models\search\MemberCompany as MemberCompanySearch;

class CompanyController extends Controller
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
	 * Lists all MemberCompany models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new MemberCompanySearch();
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

		$this->view->title = Yii::t('app', 'Companies');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'columns' => $columns,
		]);
	}

	/**
	 * Creates a new MemberCompany model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new MemberCompany();

		if(Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());
			if($model->save()) {
				Yii::$app->session->setFlash('success', Yii::t('app', 'Member company success created.'));
				return $this->redirect(['index']);
				//return $this->redirect(['view', 'id' => $model->id]);
			} 
		}

		$this->view->title = Yii::t('app', 'Create Company');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_create', [
			'model' => $model,
		]);
	}

	/**
	 * Updates an existing MemberCompany model.
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
				Yii::$app->session->setFlash('success', Yii::t('app', 'Member company success updated.'));
				return $this->redirect(['index']);
				//return $this->redirect(['view', 'id' => $model->id]);
			}
		}

		$this->view->title = Yii::t('app', 'Update {model-class}: {member-id}', ['model-class' => 'Company', 'member-id' => $model->member->displayname]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_update', [
			'model' => $model,
		]);
	}

	/**
	 * Displays a single MemberCompany model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id)
	{
		$model = $this->findModel($id);

		$this->view->title = Yii::t('app', 'Detail {model-class}: {member-id}', ['model-class' => 'Company', 'member-id' => $model->member->displayname]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_view', [
			'model' => $model,
		]);
	}

	/**
	 * Deletes an existing MemberCompany model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$model = $this->findModel($id);
		$model->publish = 2;

		if($model->save(false, ['publish'])) {
			Yii::$app->session->setFlash('success', Yii::t('app', 'Member company success deleted.'));
			return $this->redirect(['index']);
			//return $this->redirect(['view', 'id' => $model->id]);
		}
	}

	/**
	 * actionPublish an existing MemberCompany model.
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
			Yii::$app->session->setFlash('success', Yii::t('app', 'Member company success updated.'));
			return $this->redirect(['index']);
		}
	}

	/**
	 * Finds the MemberCompany model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return MemberCompany the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = MemberCompany::findOne($id)) !== null) {
			return $model;
		}

		throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}
