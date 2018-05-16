<?php
/**
 * MemberProfile
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (opensource.ommu.co)
 * @created date 2 March 2017, 09:37 WIB
 * @link https://github.com/ommu/mod-member
 *
 * This is the template for generating the model class of a specified table.
 * - $this: the ModelCode object
 * - $tableName: the table name for this class (prefix is already removed if necessary)
 * - $modelClass: the model class name
 * - $columns: list of table columns (name=>CDbColumnSchema)
 * - $labels: list of attribute labels (name=>label)
 * - $rules: list of validation rules
 * - $relations: list of relations (name=>relation declaration)
 *
 * --------------------------------------------------------------------------------------
 *
 * This is the model class for table "ommu_member_profile".
 *
 * The followings are the available columns in table 'ommu_member_profile':
 * @property integer $profile_id
 * @property integer $publish
 * @property string $profile_name
 * @property string $profile_desc
 * @property integer $multiple_user
 * @property integer $user_limit
 * @property string $creation_date
 * @property string $creation_id
 * @property string $modified_date
 * @property string $modified_id
 *
 * The followings are the available model relations:
 * @property Members[] $Members
 */
class MemberProfile extends CActiveRecord
{
	public $defaultColumns = array();
	public $title;
	public $description;
	public $user_unlimit_i;
	
	// Variable Search
	public $creation_search;
	public $modified_search;
	public $member_search;

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return MemberProfile the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ommu_member_profile';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('
				title, description, user_unlimit_i', 'required'),
			array('publish, multiple_user, user_limit,
				user_unlimit_i', 'numerical', 'integerOnly'=>true),
			array('user_limit', 'length', 'max'=>5),
			array('profile_name, profile_desc, creation_id, modified_id', 'length', 'max'=>11),
			array('
				title', 'length', 'max'=>32),
			array('
				description', 'length', 'max'=>128),
			array('profile_name, profile_desc', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('profile_id, publish, profile_name, profile_desc, multiple_user, user_limit, creation_date, creation_id, modified_date, modified_id,
				title, description, creation_search, modified_search, member_search', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'view' => array(self::BELONGS_TO, 'ViewMemberProfile', 'profile_id'),
			'title' => array(self::BELONGS_TO, 'OmmuSystemPhrase', 'profile_name'),
			'description' => array(self::BELONGS_TO, 'OmmuSystemPhrase', 'profile_desc'),
			'creation' => array(self::BELONGS_TO, 'Users', 'creation_id'),
			'modified' => array(self::BELONGS_TO, 'Users', 'modified_id'),
			'members' => array(self::HAS_MANY, 'Members', 'profile_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'profile_id' => Yii::t('attribute', 'Profile'),
			'publish' => Yii::t('attribute', 'Publish'),
			'profile_name' => Yii::t('attribute', 'Profile'),
			'profile_desc' => Yii::t('attribute', 'Description'),
			'multiple_user' => Yii::t('attribute', 'Multiple User'),
			'user_limit' => Yii::t('attribute', 'User Limit'),
			'creation_date' => Yii::t('attribute', 'Creation Date'),
			'creation_id' => Yii::t('attribute', 'Creation'),
			'modified_date' => Yii::t('attribute', 'Modified Date'),
			'modified_id' => Yii::t('attribute', 'Modified'),
			'title' => Yii::t('attribute', 'Profile'),
			'description' => Yii::t('attribute', 'Description'),
			'user_unlimit_i' => Yii::t('attribute', 'Unlimited User'),
			'creation_search' => Yii::t('attribute', 'Creation'),
			'modified_search' => Yii::t('attribute', 'Modified'),
			'member_search' => Yii::t('attribute', 'Member'),
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;
		
		// Custom Search
		$defaultLang = OmmuLanguages::getDefault('code');
		if(isset(Yii::app()->session['language']))
			$language = Yii::app()->session['language'];
		else 
			$language = $defaultLang;
		
		$criteria->with = array(
			'view' => array(
				'alias'=>'view',
			),
			'title' => array(
				'alias'=>'title',
				'select'=>$language,
			),
			'description' => array(
				'alias'=>'description',
				'select'=>$language,
			),
			'creation' => array(
				'alias'=>'creation',
				'select'=>'displayname'
			),
			'modified' => array(
				'alias'=>'modified',
				'select'=>'displayname'
			),
		);

		$criteria->compare('t.profile_id',$this->profile_id);
		if(Yii::app()->getRequest()->getParam('type') == 'publish')
			$criteria->compare('t.publish',1);
		elseif(Yii::app()->getRequest()->getParam('type') == 'unpublish')
			$criteria->compare('t.publish',0);
		elseif(Yii::app()->getRequest()->getParam('type') == 'trash')
			$criteria->compare('t.publish',2);
		else {
			$criteria->addInCondition('t.publish',array(0,1));
			$criteria->compare('t.publish',$this->publish);
		}
		$criteria->compare('t.profile_name',$this->profile_name);
		$criteria->compare('t.profile_desc',$this->profile_desc);
		$criteria->compare('t.multiple_user',$this->multiple_user);
		$criteria->compare('t.user_limit',$this->user_limit);
		if($this->creation_date != null && !in_array($this->creation_date, array('0000-00-00 00:00:00', '0000-00-00')))
			$criteria->compare('date(t.creation_date)',date('Y-m-d', strtotime($this->creation_date)));
		if(isset($_GET['creation']))
			$criteria->compare('t.creation_id',$_GET['creation']);
		else
			$criteria->compare('t.creation_id',$this->creation_id);
		if($this->modified_date != null && !in_array($this->modified_date, array('0000-00-00 00:00:00', '0000-00-00')))
			$criteria->compare('date(t.modified_date)',date('Y-m-d', strtotime($this->modified_date)));
		if(Yii::app()->getRequest()->getParam('modified'))
			$criteria->compare('t.modified_id',$_GET['modified']);
		else
			$criteria->compare('t.modified_id',$this->modified_id);
		
		$criteria->compare('title.'.$language,strtolower($this->title), true);
		$criteria->compare('description.'.$language,strtolower($this->description), true);
		$criteria->compare('creation.displayname',strtolower($this->creation_search), true);
		$criteria->compare('modified.displayname',strtolower($this->modified_search), true);
		$criteria->compare('view.members',$this->member_search);

		if(!isset($_GET['MemberProfile_sort']))
			$criteria->order = 't.profile_id DESC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
				'pageSize'=>30,
			),
		));
	}


	/**
	 * Get column for CGrid View
	 */
	public function getGridColumn($columns=null) {
		if($columns !== null) {
			foreach($columns as $val) {
				/*
				if(trim($val) == 'enabled') {
					$this->defaultColumns[] = array(
						'name'  => 'enabled',
						'value' => '$data->enabled == 1? "Ya": "Tidak"',
					);
				}
				*/
				$this->defaultColumns[] = $val;
			}
		} else {
			//$this->defaultColumns[] = 'profile_id';
			$this->defaultColumns[] = 'publish';
			$this->defaultColumns[] = 'profile_name';
			$this->defaultColumns[] = 'profile_desc';
			$this->defaultColumns[] = 'multiple_user';
			$this->defaultColumns[] = 'user_limit';
			$this->defaultColumns[] = 'creation_date';
			$this->defaultColumns[] = 'creation_id';
			$this->defaultColumns[] = 'modified_date';
			$this->defaultColumns[] = 'modified_id';
		}

		return $this->defaultColumns;
	}

	/**
	 * Set default columns to display
	 */
	protected function afterConstruct() {
		if(count($this->defaultColumns) == 0) {
			/*
			$this->defaultColumns[] = array(
				'class' => 'CCheckBoxColumn',
				'name' => 'id',
				'selectableRows' => 2,
				'checkBoxHtmlOptions' => array('name' => 'trash_id[]')
			);
			*/
			$this->defaultColumns[] = array(
				'header' => 'No',
				'value' => '$this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize + $row+1'
			);
			$this->defaultColumns[] = array(
				'name' => 'title',
				'value' => 'Phrase::trans($data->profile_name)',
			);
			/*
			$this->defaultColumns[] = array(
				'name' => 'description',
				'value' => 'Phrase::trans($data->profile_desc)',
			);
			*/
			$this->defaultColumns[] = array(
				'name' => 'user_limit',
				'value' => '$data->multiple_user == 1 ? ($data->user_limit != 0 ? $data->user_limit : Yii::t(\'phrase\', \'Unlimited\')) : \'-\'',
				'htmlOptions' => array(
					'class' => 'center',
				),
			);
			$this->defaultColumns[] = array(
				'name' => 'creation_search',
				'value' => '$data->creation->displayname',
			);
			$this->defaultColumns[] = array(
				'name' => 'creation_date',
				'value' => 'Utility::dateFormat($data->creation_date)',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => Yii::app()->controller->widget('application.libraries.core.components.system.CJuiDatePicker', array(
					'model'=>$this,
					'attribute'=>'creation_date',
					'language' => 'en',
					'i18nScriptFile' => 'jquery-ui-i18n.min.js',
					//'mode'=>'datetime',
					'htmlOptions' => array(
						'id' => 'creation_date_filter',
					),
					'options'=>array(
						'showOn' => 'focus',
						'dateFormat' => 'yy-mm-dd',
						'showOtherMonths' => true,
						'selectOtherMonths' => true,
						'changeMonth' => true,
						'changeYear' => true,
						'showButtonPanel' => true,
					),
				), true),
			);
			$this->defaultColumns[] = array(
				'name' => 'member_search',
				'value' => 'CHtml::link($data->view->members, Yii::app()->controller->createUrl("o/admin/manage",array(\'profile\'=>$data->profile_id,\'type\'=>\'publish\')))',
				'htmlOptions' => array(
					'class' => 'center',
				),	
				'type' => 'raw',
			);
			$this->defaultColumns[] = array(
				'name' => 'multiple_user',
				'value' => '$data->multiple_user == \'1\' ? CHtml::image(Yii::app()->theme->baseUrl.\'/images/icons/publish.png\') : CHtml::image(Yii::app()->theme->baseUrl.\'/images/icons/unpublish.png\')',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter'=>array(
					1=>Yii::t('phrase', 'Yes'),
					0=>Yii::t('phrase', 'No'),
				),
				'type' => 'raw',
			);
			if(!Yii::app()->getRequest()->getParam('type')) {
				$this->defaultColumns[] = array(
					'name' => 'publish',
					'value' => 'Utility::getPublish(Yii::app()->controller->createUrl("publish",array("id"=>$data->profile_id)), $data->publish, 1)',
					'htmlOptions' => array(
						'class' => 'center',
					),
					'filter'=>array(
						1=>Yii::t('phrase', 'Yes'),
						0=>Yii::t('phrase', 'No'),
					),
					'type' => 'raw',
				);
			}			
		}
		parent::afterConstruct();
	}

	/**
	 * User get information
	 */
	public static function getInfo($id, $column=null)
	{
		if($column != null) {
			$model = self::model()->findByPk($id,array(
				'select' => $column
			));
			return $model->$column;
			
		} else {
			$model = self::model()->findByPk($id);
			return $model;			
		}
	}

	/**
	 * Get member profile
	 * 0 = unpublish
	 * 1 = publish
	 */
	public static function getProfile($publish=null, $multipleuser=null, $type=null) 
	{
		$criteria=new CDbCriteria;
		if($publish != null)
			$criteria->compare('publish', $publish);
		if($multipleuser != null)
			$criteria->compare('multiple_user', $multipleuser);
		
		$model = self::model()->findAll($criteria);

		$items = array();
		if($model != null) {
			foreach($model as $key => $val) {
				if($type == null)
					$items[$val->profile_id] = Phrase::trans($val->profile_name);
				else if($type != null && $type == 'id')
					$items[] = $val->profile_id;
			}
			return $items;
		} else {
			return false;
		}
	}

	/**
	 * before validate attributes
	 */
	protected function beforeValidate() {
		if(parent::beforeValidate()) {
			if($this->user_unlimit_i == 1)
				$this->user_limit = 0;
			
			if($this->user_unlimit_i == 0 && $this->user_limit != '' && $this->user_limit <= 0)
				$this->addError('user_limit', Yii::t('phrase', 'User Limit harus lebih besar dari 0'));
			
			if($this->isNewRecord)
				$this->creation_id = Yii::app()->user->id;	
			else
				$this->modified_id = Yii::app()->user->id;
		}
		return true;
	}
	
	/**
	 * before save attributes
	 */
	protected function beforeSave() 
	{
		$currentModule = strtolower(Yii::app()->controller->module->id.'/'.Yii::app()->controller->id);
		$location = Utility::getUrlTitle($currentModule);
		
		if(parent::beforeSave()) {
			if($this->isNewRecord || (!$this->isNewRecord && $this->profile_name == 0)) {
				$title=new OmmuSystemPhrase;
				$title->location = $location.'_title';
				$title->en_us = $this->title;
				if($title->save())
					$this->profile_name = $title->phrase_id;
				
			} else {
				$title = OmmuSystemPhrase::model()->findByPk($this->profile_name);
				$title->en_us = $this->title;
				$title->save();
			}
			
			if($this->isNewRecord || (!$this->isNewRecord && $this->profile_desc == 0)) {
				$desc=new OmmuSystemPhrase;
				$desc->location = $location.'_description';
				$desc->en_us = $this->description;
				if($desc->save())
					$this->profile_desc = $desc->phrase_id;
				
			} else {
				$desc = OmmuSystemPhrase::model()->findByPk($this->profile_desc);
				$desc->en_us = $this->description;
				$desc->save();
			}
		}
		return true;
	}

}