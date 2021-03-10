<?php
/**
 * DocumentController
 * @var $this ommu\member\controllers\setting\profile\DocumentController
 * @var $model ommu\member\models\MemberProfileDocument
 *
 * DocumentController implements the CRUD actions for MemberProfileDocument model.
 * Reference start
 * TOC :
 *	Index
 *	Manage
 *	Create
 *	Update
 *	View
 *	Delete
 *	RunAction
 *	Publish
 *
 *	findModel
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 OMMU (www.ommu.id)
 * @created date 2 October 2018, 11:36 WIB
 * @modified date 2 September 2019, 18:28 WIB
 * @link https://github.com/ommu/mod-member
 *
 */

namespace ommu\member\controllers\setting\profile;

use Yii;
use app\components\Controller;
use mdm\admin\components\AccessControl;
use yii\filters\VerbFilter;
use ommu\member\models\MemberProfileDocument;
use ommu\member\models\search\MemberProfileDocument as MemberProfileDocumentSearch;

class DocumentController extends Controller
{
	/**
	 * {@inheritdoc}
	 */
	public function init()
	{
        parent::init();

        if (Yii::$app->request->get('id') || Yii::$app->request->get('profile')) {
            $this->subMenu = $this->module->params['profile_submenu'];
        }
        if (Yii::$app->request->get('document')) {
            $this->subMenu = $this->module->params['setting_submenu'];
        }
	}

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
	 * {@inheritdoc}
	 */
	public function actionIndex()
	{
        return $this->redirect(['manage']);
	}

	/**
	 * Lists all MemberProfileDocument models.
	 * @return mixed
	 */
	public function actionManage()
	{
        $searchModel = new MemberProfileDocumentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $gridColumn = Yii::$app->request->get('GridColumn', null);
        $cols = [];
        if ($gridColumn != null && count($gridColumn) > 0) {
            foreach ($gridColumn as $key => $val) {
                if ($gridColumn[$key] == 1) {
                    $cols[] = $key;
                }
            }
        }
        $columns = $searchModel->getGridColumn($cols);

        if (($document = Yii::$app->request->get('document')) != null) {
            $document = \ommu\member\models\MemberDocumentType::findOne($document);
        }
        if (($profile = Yii::$app->request->get('profile')) != null) {
            $this->subMenuParam = $profile;
			$profile = \ommu\member\models\MemberProfile::findOne($profile);
		}

		$this->view->title = Yii::t('app', 'Profile Documents');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_manage', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'columns' => $columns,
			'document' => $document,
			'profile' => $profile,
		]);
	}

	/**
	 * Creates a new MemberProfileDocument model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
        if (($id = Yii::$app->request->get('id')) == null) {
            throw new \yii\web\ForbiddenHttpException(Yii::t('app', 'The requested page does not exist.'));
        }

		$model = new MemberProfileDocument();
		$model->profile_id = $id;

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            // $postData = Yii::$app->request->post();
            // $model->load($postData);
            // $model->order = $postData['order'] ? $postData['order'] : 0;

            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Member profile document success created.'));
                if (!Yii::$app->request->isAjax) {
					return $this->redirect(['manage', 'profile' => $model->profile_id]);
                }
                return $this->redirect(Yii::$app->request->referrer ?: ['manage', 'profile' => $model->profile_id]);
                //return $this->redirect(['view', 'id' => $model->id]);

            } else {
                if (Yii::$app->request->isAjax) {
                    return \yii\helpers\Json::encode(\app\components\widgets\ActiveForm::validate($model));
                }
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
		$this->subMenuParam = $model->profile_id;

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            // $postData = Yii::$app->request->post();
            // $model->load($postData);
            // $model->order = $postData['order'] ? $postData['order'] : 0;

            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Member profile document success updated.'));
                if (!Yii::$app->request->isAjax) {
                    return $this->redirect(['update', 'id' => $model->id]);
                }
                return $this->redirect(Yii::$app->request->referrer ?: ['manage', 'profile' => $model->profile_id]);

            } else {
                if (Yii::$app->request->isAjax) {
                    return \yii\helpers\Json::encode(\app\components\widgets\ActiveForm::validate($model));
                }
            }
        }

		$this->view->title = Yii::t('app', 'Update Profile Document: {profile-id}', ['profile-id' => $model->profile->title->message]);
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
		$this->subMenuParam = $model->profile_id;

		$this->view->title = Yii::t('app', 'Detail Profile Document: {profile-id}', ['profile-id' => $model->profile->title->message]);
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

        if ($model->save(false, ['publish', 'modified_id'])) {
			Yii::$app->session->setFlash('success', Yii::t('app', 'Member profile document success deleted.'));
			return $this->redirect(Yii::$app->request->referrer ?: ['manage', 'profile' => $model->profile_id]);
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

        if ($model->save(false, ['publish', 'modified_id'])) {
			Yii::$app->session->setFlash('success', Yii::t('app', 'Member profile document success updated.'));
			return $this->redirect(Yii::$app->request->referrer ?: ['manage', 'profile' => $model->profile_id]);
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
        if (($model = MemberProfileDocument::findOne($id)) !== null) {
            return $model;
        }

		throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}
