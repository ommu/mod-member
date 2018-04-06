<?php
/**
 * AdminController
 * @var $this AdminController
 * @var $model Members
 * @var $form CActiveForm
 *
 * Reference start
 * TOC :
 *	Index
 *	Manage
 *	Add
 *	Edit
 *	View
 *	RunAction
 *	Delete
 *	Publish
 *	Private
 *
 *	LoadModel
 *	performAjaxValidation
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (opensource.ommu.co)
 * @created date 2 March 2017, 15:02 WIB
 * @link https://github.com/ommu/mod-member
 *
 *----------------------------------------------------------------------------------------------------------
 */

class AdminController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	//public $layout='//layouts/column2';
	public $defaultAction = 'index';

	/**
	 * Initialize admin page theme
	 */
	public function init() 
	{
		if(!Yii::app()->user->isGuest) {
			if(in_array(Yii::app()->user->level, array(1,2))) {
				$arrThemes = Utility::getCurrentTemplate('admin');
				Yii::app()->theme = $arrThemes['folder'];
				$this->layout = $arrThemes['layout'];
			}
		} else
			$this->redirect(Yii::app()->createUrl('site/login'));
	}

	/**
	 * @return array action filters
	 */
	public function filters() 
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			//'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules() 
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array(),
				'users'=>array('@'),
				'expression'=>'isset(Yii::app()->user->level)',
				//'expression'=>'isset(Yii::app()->user->level) && (Yii::app()->user->level != 1)',
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('manage','add','edit','view','runaction','delete','publish','private'),
				'users'=>array('@'),
				'expression'=>'isset(Yii::app()->user->level) && in_array(Yii::app()->user->level, array(1,2))',
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array(),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	
	/**
	 * Lists all models.
	 */
	public function actionIndex() 
	{
		$this->redirect(array('manage'));
	}

	/**
	 * Manages all models.
	 */
	public function actionManage() 
	{
		$model=new Members('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Members'])) {
			$model->attributes=$_GET['Members'];
		}

		$columnTemp = array();
		if(isset($_GET['GridColumn'])) {
			foreach($_GET['GridColumn'] as $key => $val) {
				if($_GET['GridColumn'][$key] == 1) {
					$columnTemp[] = $key;
				}
			}
		}
		$columns = $model->getGridColumn($columnTemp);

		$this->pageTitle = Yii::t('phrase', 'Members Manage');
		$this->pageDescription = '';
		$this->pageMeta = '';
		$this->render('admin_manage',array(
			'model'=>$model,
			'columns' => $columns,
		));
	}	
	
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionAdd($type=null) 
	{
		$model=new Members;
		
		if($type == null) {
			// Uncomment the following line if AJAX validation is needed
			$this->performAjaxValidation($model);
			
			if(isset($_POST['Members'])) {				
				$model->attributes=$_POST['Members'];
				
				if($model->validate()) {
					$this->redirect(array('add','type'=>$model->profile_id,'t'=>Utility::getUrlTitle(Phrase::trans($model->profile->profile_name)),'email'=>$model->member_user_i));
				}
			}
			$dataArray = array(
				'model'=>$model,
			);
			
		} else {
			$profile = MemberProfile::getInfo($type);
			$condition = 0;
			if($profile->multiple_user == 1)
				$condition = 1;
			
			$users = Users::model()->findByAttributes(array('email' => $_GET['email']));
			if($users == null)
				$users=new Users;
			if($condition == 1)
				$memberCompany=new MemberCompany;
			$memberUser=new MemberUser;
			$setting = OmmuSettings::model()->findByPk(1, array(
				'select'=>'signup_username, signup_approve, signup_verifyemail, signup_photo, signup_random',
			));
			$memberSetting = MemberSetting::model()->findByPk(1, array(
				'select'=>'default_user_level, form_custom_insert_field',
			));
			$form_custom_insert_field = unserialize($memberSetting->form_custom_insert_field);
			if(empty($form_custom_insert_field))
				$form_custom_insert_field = array();
			
			$dataArray = array(
				'model'=>$model,
				'users'=>$users,
				'setting'=>$setting,
				'form_custom'=>$form_custom_insert_field,
			);
			if($condition == 1)
				$dataArray['memberCompany'] = $memberCompany;
			
			// Uncomment the following line if AJAX validation is needed
			$this->performAjaxValidation($model);
			$this->performAjaxValidation($users);
			if($condition == 1)
				$this->performAjaxValidation($memberCompany);
			$this->performAjaxValidation($memberUser);
			
			$model->profile_id = $profile->profile_id;
			
			if(isset($_POST['Members'])) {
				$model->attributes=$_POST['Members'];
				$users->attributes=$_POST['Users'];
				if($users->user_id == null)
					$users->scenario = 'formAdd';
				if($condition == 1)
					$memberCompany->attributes=$_POST['MemberCompany'];
				$memberUser->attributes=$_POST['MemberUser'];
				
				if($users->user_id == null)
					$users->level_id = $memberSetting->default_user_level;
				if($users->validate())
					$model->publish = $users->enabled;
				
				if($condition == 0) {
					if($users->user_id != null) {
						$data = MemberUser::model()->with('member','level')->find(array(
							'select'    => 't.id, t.member_id, t.level_id, t.user_id',
							'condition' => 'member.profile_id = :profile AND level.default = :level AND t.user_id = :user',
							'params'    => array(
								':profile' => $profile->profile_id,
								':level' => 1,
								':user' => $users->user_id,
							),
						));
						if($data != null) {
							Yii::app()->user->setFlash('Users_displayname_em_', Yii::t('phrase', 'User sudah terdaftar sebagai member personal'));
							$this->redirect(Yii::app()->controller->createUrl('add', $_GET));		
						}
					}
					if($model->validate() && $users->validate()) {
						if($model->save() && $users->save()) {
							$memberUser->member_id = $model->member_id;
							$memberUser->user_id = $users->user_id;
							$memberUser->validate();
							if($memberUser->save()) {
								Yii::app()->user->setFlash('success', Yii::t('phrase', 'Members success created.'));
								//$this->redirect(array('view','id'=>$model->member_id));
								//$this->redirect(array('manage'));
								$this->redirect(Yii::app()->controller->createUrl('manage'));						
							}
						}
					}
				} else if($condition == 1) {
					if($model->validate() && $users->validate() && $memberCompany->validate()) {
						if($model->save() && $users->save()) {
							$memberUser->member_id = $model->member_id;
							$memberUser->user_id = $users->user_id;
							$memberUser->validate();
							
							$memberCompany->member_id = $model->member_id;
							
							if($memberUser->save() && $memberCompany->save()) {
								Yii::app()->user->setFlash('success', Yii::t('phrase', 'Members success created.'));
								//$this->redirect(array('view','id'=>$model->member_id));
								//$this->redirect(array('manage'));
								$this->redirect(Yii::app()->controller->createUrl('manage'));						
							}
						}
					}
				}
			}
		}
		
		$this->dialogDetail = true;
		$this->dialogGroundUrl = Yii::app()->controller->createUrl('manage');
		$this->dialogWidth = $type == null ? 500 : 600;

		$this->pageTitle = Yii::t('phrase', 'Create Members');
		$this->pageDescription = '';
		$this->pageMeta = '';
		$this->render($type == null ? 'admin_pre_add' : 'admin_add', $type == null ? $dataArray : array('data'=>$dataArray));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionEdit($id) 
	{
		$condition = 0;
		$model=$this->loadModel($id);
		if($model->profile->multiple_user == 1)
			$condition = 1;
		
		$users = Users::model()->findByPk($model->view->user_id);
		$setting = OmmuSettings::model()->findByPk(1, array(
			'select'=>'signup_username, signup_approve, signup_verifyemail, signup_photo, signup_random',
		));
		$memberSetting = MemberSetting::model()->findByPk(1, array(
			'select'=>'default_user_level, form_custom_insert_field',
		));
		$form_custom_insert_field = unserialize($memberSetting->form_custom_insert_field);
		if(empty($form_custom_insert_field))
			$form_custom_insert_field = array();
			
		$dataArray = array(
			'model'=>$model,
			'users'=>$users,
			'setting'=>$setting,
			'form_custom'=>$form_custom_insert_field,
		);
		if($condition == 1) {
			$memberCompany = MemberCompany::model()->find(array(
				'select'    => 't.id, t.member_id, t.company_id',
				'condition' => 't.member_id = :member AND t.company_id = :company',
				'params'    => array(
					':member' => $model->member_id,
					':company' => $model->view->company_id,
				),
			));
			$dataArray['memberCompany'] = $memberCompany;
		}

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);
		$this->performAjaxValidation($users);
		if($condition == 1)
			$this->performAjaxValidation($memberCompany);
			
		if(isset($_POST['Members'])) {
			$model->attributes=$_POST['Members'];
			$users->attributes=$_POST['Users'];
			if($condition == 0)
				$users->scenario = 'formEdit';
			if($condition == 1)
				$memberCompany->attributes=$_POST['MemberCompany'];
			
			if($users->validate())
				$model->publish = $users->enabled;
			
			if($condition == 0) {
				if($model->validate() && $users->validate()) {
					if($model->save() && $users->save()) {
						Yii::app()->user->setFlash('success', Yii::t('phrase', 'Members success updated.'));
						//$this->redirect(array('view','id'=>$model->member_id));
						//$this->redirect(array('manage'));
						$this->redirect(Yii::app()->controller->createUrl('manage'));
					}
				}				
			} else if($condition == 1) {
				if($model->validate() && $users->validate() && $memberCompany->validate()) {
					if($model->save() && $users->save() && $memberCompany->save()) {
						Yii::app()->user->setFlash('success', Yii::t('phrase', 'Members success updated.'));
						//$this->redirect(array('view','id'=>$model->member_id));
						//$this->redirect(array('manage'));
						$this->redirect(Yii::app()->controller->createUrl('manage'));
					}
				}				
			}
		}
		
		$this->dialogDetail = true;
		$this->dialogGroundUrl = Yii::app()->controller->createUrl('manage');
		$this->dialogWidth = 600;

		$this->pageTitle = Yii::t('phrase', 'Update Members');
		$this->pageDescription = '';
		$this->pageMeta = '';
		$this->render('admin_edit', array('data'=>$dataArray));
	}
	
	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id) 
	{
		$model=$this->loadModel($id);
		
		$this->dialogDetail = true;
		$this->dialogGroundUrl = Yii::app()->controller->createUrl('manage');
		$this->dialogWidth = 600;

		$this->pageTitle = Yii::t('phrase', 'View Members');
		$this->pageDescription = '';
		$this->pageMeta = '';
		$this->render('admin_view',array(
			'model'=>$model,
		));
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionRunAction() {
		$id       = $_POST['trash_id'];
		$criteria = null;
		$actions  = $_GET['action'];

		if(count($id) > 0) {
			$criteria = new CDbCriteria;
			$criteria->addInCondition('id', $id);

			if($actions == 'publish') {
				Members::model()->updateAll(array(
					'publish' => 1,
				),$criteria);
			} elseif($actions == 'unpublish') {
				Members::model()->updateAll(array(
					'publish' => 0,
				),$criteria);
			} elseif($actions == 'trash') {
				Members::model()->updateAll(array(
					'publish' => 2,
				),$criteria);
			} elseif($actions == 'delete') {
				Members::model()->deleteAll($criteria);
			}
		}

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax'])) {
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('manage'));
		}
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id) 
	{
		$model=$this->loadModel($id);
		
		if(Yii::app()->request->isPostRequest) {
			// we only allow deletion via POST request
			if(isset($id)) {
				if($model->delete()) {
					echo CJSON::encode(array(
						'type' => 5,
						'get' => Yii::app()->controller->createUrl('manage'),
						'id' => 'partial-members',
						'msg' => '<div class="errorSummary success"><strong>'.Yii::t('phrase', 'Members success deleted.').'</strong></div>',
					));
				}
			}

		} else {
			$this->dialogDetail = true;
			$this->dialogGroundUrl = Yii::app()->controller->createUrl('manage');
			$this->dialogWidth = 350;

			$this->pageTitle = Yii::t('phrase', 'Members Delete.');
			$this->pageDescription = '';
			$this->pageMeta = '';
			$this->render('admin_delete');
		}
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionPublish($id) 
	{
		$model=$this->loadModel($id);
		
		if($model->publish == 1) {
			$title = Yii::t('phrase', 'Unpublish');
			$replace = 0;
		} else {
			$title = Yii::t('phrase', 'Publish');
			$replace = 1;
		}

		if(Yii::app()->request->isPostRequest) {
			// we only allow deletion via POST request
			if(isset($id)) {
				//change value active or publish
				$model->publish = $replace;

				if($model->update()) {
					echo CJSON::encode(array(
						'type' => 5,
						'get' => Yii::app()->controller->createUrl('manage'),
						'id' => 'partial-members',
						'msg' => '<div class="errorSummary success"><strong>'.Yii::t('phrase', 'Members success updated.').'</strong></div>',
					));
				}
			}

		} else {
			$this->dialogDetail = true;
			$this->dialogGroundUrl = Yii::app()->controller->createUrl('manage');
			$this->dialogWidth = 350;

			$this->pageTitle = $title;
			$this->pageDescription = '';
			$this->pageMeta = '';
			$this->render('admin_publish',array(
				'title'=>$title,
				'model'=>$model,
			));
		}
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionPrivate($id) 
	{
		$model=$this->loadModel($id);
		
		if($model->member_private == 1) {
			$title = Yii::t('phrase', 'Public Member');
			$replace = 0;
		} else {
			$title = Yii::t('phrase', 'Private Member');
			$replace = 1;
		}

		if(Yii::app()->request->isPostRequest) {
			// we only allow deletion via POST request
			if(isset($id)) {
				//change value member_private
				$model->member_private = $replace;

				if($model->update()) {
					echo CJSON::encode(array(
						'type' => 5,
						'get' => Yii::app()->controller->createUrl('manage'),
						'id' => 'partial-members',
						'msg' => '<div class="errorSummary success"><strong>'.Yii::t('phrase', 'Members success updated.').'</strong></div>',
					));
				}
			}

		} else {
			$this->dialogDetail = true;
			$this->dialogGroundUrl = Yii::app()->controller->createUrl('manage');
			$this->dialogWidth = 350;

			$this->pageTitle = $title;
			$this->pageDescription = '';
			$this->pageMeta = '';
			$this->render('admin_private',array(
				'title'=>$title,
				'model'=>$model,
			));
		}
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id) 
	{
		$model = Members::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404, Yii::t('phrase', 'The requested page does not exist.'));
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model) 
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='members-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
