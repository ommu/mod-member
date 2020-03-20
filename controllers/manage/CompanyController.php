<?php
/**
 * CompanyController
 * @var $this ommu\member\controllers\manage\CompanyController
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
 *
 *	findModel
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 OMMU (www.ommu.id)
 * @created date 31 October 2018, 15:48 WIB
 * @link https://github.com/ommu/mod-member
 *
 */

namespace ommu\member\controllers\manage;

use Yii;
use app\components\Controller;
use mdm\admin\components\AccessControl;
use yii\filters\VerbFilter;
use ommu\member\models\MemberCompany;
use ommu\member\models\search\MemberCompany as MemberCompanySearch;
use yii\base\Model;
use ommu\member\models\Members;
use app\modules\ipedia\models\IpediaCompany as IpediaCompanies;

class CompanyController extends Controller
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
		$member = new Members();
		$member->scenario = Members::SCENARIO_MEMBER_COMPANY;

		if(Yii::$app->request->isPost) {
			$postData = Yii::$app->request->post();
			$model->load($postData);
			$member->load($postData);
			$member->profile_id = 3;

			if(Model::validateMultiple([$model, $member])) {
				$model->company_city_id = $postData['company_city_id'] ? $postData['company_city_id'] : 0;
				$model->company_province_id = $postData['company_province_id'] ? $postData['company_province_id'] : 0;
				$model->company_country_id = $postData['company_country_id'] ? $postData['company_country_id'] : 0;
				$model->company_zipcode = $postData['company_zipcode'] ? $postData['company_zipcode'] : 0;
				
				if($member->save()) {
					$model->member_id = $member->member_id;
					$company_id = IpediaCompanies::createCompany($member->displayname);
					if(($company = IpediaCompanies::findOne($company_id)) !== null) {
						$company->member_id = $member->member_id;
						$company->save();
					}
					$model->company_id = $company_id;

					if($model->save()) {
						Yii::$app->session->setFlash('success', Yii::t('app', 'Member company success created.'));
						return $this->redirect(['index']);
						//return $this->redirect(['view', 'id'=>$model->id]);
					}
				}
			}
		}

		$this->view->title = Yii::t('app', 'Create Company');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_create', [
			'model' => $model,
			'member' => $member,
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
		$model->scenario = MemberCompany::SCENARIO_UPDATE;
		$member = Members::findOne($model->member_id);
		$member->scenario = Members::SCENARIO_MEMBER_COMPANY;

		if(Yii::$app->request->isPost) {
			$postData = Yii::$app->request->post();
			$model->load($postData);
			$member->load($postData);

			if(Model::validateMultiple([$model, $member])) {
				if($member->save()) {
					if($model->save()) {
						Yii::$app->session->setFlash('success', Yii::t('app', 'Member company success updated.'));
						return $this->redirect(['index']);
						//return $this->redirect(['view', 'id'=>$model->id]);
					}
				}
			}
		}

		$this->view->title = Yii::t('app', 'Update Company: {member-id}', ['member-id' => $model->member->displayname]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_update', [
			'model' => $model,
			'member' => $member,
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

		$this->view->title = Yii::t('app', 'Detail Company: {member-id}', ['member-id' => $model->member->displayname]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_view', [
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
		$model->delete();

		Yii::$app->session->setFlash('success', Yii::t('app', 'Member company success deleted.'));
		return $this->redirect(['index']);
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
		if(($model = MemberCompany::findOne($id)) !== null)
			return $model;

		throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}
