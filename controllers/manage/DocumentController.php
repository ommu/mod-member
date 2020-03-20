<?php
/**
 * DocumentController
 * @var $this ommu\member\controllers\manage\DocumentController
 * @var $model ommu\member\models\MemberDocuments
 *
 * DocumentController implements the CRUD actions for MemberDocuments model.
 * Reference start
 * TOC :
 *	Index
 *	Create
 *	Update
 *	View
 *	Delete
 *	RunAction
 *	Publish
 *	Status
 *
 *	findModel
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 OMMU (www.ommu.id)
 * @created date 1 November 2018, 09:12 WIB
 * @link https://github.com/ommu/mod-member
 *
 */

namespace ommu\member\controllers\manage;

use Yii;
use app\components\Controller;
use mdm\admin\components\AccessControl;
use yii\filters\VerbFilter;
use ommu\member\models\MemberDocuments;
use ommu\member\models\search\MemberDocuments as MemberDocumentsSearch;
use ommu\member\models\Members;

class DocumentController extends Controller
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
					'status' => ['POST'],
				],
			],
		];
	}

	/**
	 * Lists all MemberDocuments models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new MemberDocumentsSearch();
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

		$this->view->title = Yii::t('app', 'Documents');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'columns' => $columns,
		]);
	}

	/**
	 * Creates a new MemberDocuments model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$member = Yii::$app->request->get('member');
		if(!$member)
			throw new \yii\web\ForbiddenHttpException(Yii::t('app', 'The requested page does not exist.'));

		$model = new MemberDocuments();
		$memberFind = Members::findOne($member);
		$document = \yii\helpers\ArrayHelper::map($memberFind->profile->documents, 'id', 'document_name_i');

		if(Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());
			$model->member_id = $member;

			if($model->save()) {
				Yii::$app->session->setFlash('success', Yii::t('app', 'Member document success created.'));
				return $this->redirect(['index', 'member'=>$model->member_id]);
				//return $this->redirect(['view', 'id'=>$model->id]);

			} else {
				if(Yii::$app->request->isAjax)
					return \yii\helpers\Json::encode(\app\components\widgets\ActiveForm::validate($model));
			}
		}

		$this->view->title = Yii::t('app', 'Create Document');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_create', [
			'model' => $model,
			'document' => $document,
		]);
	}

	/**
	 * Updates an existing MemberDocuments model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		$model = $this->findModel($id);
		$document = \yii\helpers\ArrayHelper::map($model->member->profile->documents, 'id', 'document_name_i');

		if(Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());
			// $postData = Yii::$app->request->post();
			// $model->load($postData);
			// $model->order = $postData['order'] ? $postData['order'] : 0;

			if($model->save()) {
				Yii::$app->session->setFlash('success', Yii::t('app', 'Member document success updated.'));
				return $this->redirect(['index', 'member'=>$model->member_id]);
				//return $this->redirect(['view', 'id'=>$model->id]);

			} else {
				if(Yii::$app->request->isAjax)
					return \yii\helpers\Json::encode(\app\components\widgets\ActiveForm::validate($model));
			}
		}

		$this->view->title = Yii::t('app', 'Update Document: {member-id}', ['member-id' => $model->member->displayname]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_update', [
			'model' => $model,
			'document' => $document,
		]);
	}

	/**
	 * Displays a single MemberDocuments model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id)
	{
		$model = $this->findModel($id);

		$this->view->title = Yii::t('app', 'Detail Document: {member-id}', ['member-id' => $model->member->displayname]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_view', [
			'model' => $model,
		]);
	}

	/**
	 * Deletes an existing MemberDocuments model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$model = $this->findModel($id);
		$model->publish = 2;

		if($model->save(false, ['publish','modified_id'])) {
			Yii::$app->session->setFlash('success', Yii::t('app', 'Member document success deleted.'));
			return $this->redirect(['index', 'member'=>$model->member_id]);
		}
	}

	/**
	 * actionPublish an existing MemberDocuments model.
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
			Yii::$app->session->setFlash('success', Yii::t('app', 'Member document success updated.'));
			return $this->redirect(['index', 'member'=>$model->member_id]);
		}
	}

	/**
	 * Finds the MemberDocuments model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return MemberDocuments the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if(($model = MemberDocuments::findOne($id)) !== null)
			return $model;

		throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}
