<?php
/**
 * MemberUserDetail
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @created date 2 March 2017, 09:36 WIB
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
 * This is the model class for table "ommu_member_user_detail".
 *
 * The followings are the available columns in table 'ommu_member_user_detail':
 * @property string $id
 * @property integer $publish
 * @property string $member_user_id
 * @property string $updated_date
 * @property string $updated_id
 *
 * The followings are the available model relations:
 * @property MemberUser $memberUser
 */
class MemberUserDetail extends CActiveRecord
{
	use GridViewTrait;

	public $defaultColumns = array();
	
	// Variable Search
	public $profile_search;
	public $member_search;
	public $level_search;
	public $user_search;
	public $updated_search;

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return MemberUserDetail the static model class
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
		return 'ommu_member_user_detail';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('publish, member_user_id', 'required'),
			array('publish', 'numerical', 'integerOnly'=>true),
			array('member_user_id, updated_id', 'length', 'max'=>11),
			array('', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, publish, member_user_id, updated_date, updated_id,
				profile_search, member_search, level_search, user_search, updated_search', 'safe', 'on'=>'search'),
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
			'user' => array(self::BELONGS_TO, 'MemberUser', 'member_user_id'),
			'updated' => array(self::BELONGS_TO, 'Users', 'updated_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('attribute', 'ID'),
			'publish' => Yii::t('attribute', 'Publish'),
			'member_user_id' => Yii::t('attribute', 'Member User'),
			'updated_date' => Yii::t('attribute', 'Updated Date'),
			'updated_id' => Yii::t('attribute', 'Updated'),
			'profile_search' => Yii::t('attribute', 'Profile'),
			'member_search' => Yii::t('attribute', 'Member'),
			'level_search' => Yii::t('attribute', 'Level'),
			'user_search' => Yii::t('attribute', 'User'),
			'updated_search' => Yii::t('attribute', 'Updated'),
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
		$criteria->with = array(
			'user' => array(
				'alias'=>'user',
			),
			'user.member' => array(
				'alias'=>'member',
				'select'=>'publish, profile_id'
			),
			'user.member.view' => array(
				'alias'=>'member_v',
				'select'=>'member_name'
			),
			'user.user' => array(
				'alias'=>'user_user',
				'select'=>'displayname',
			),
			'updated' => array(
				'alias'=>'updated',
				'select'=>'displayname'
			),
		);

		$criteria->compare('t.id', $this->id);
		if(Yii::app()->getRequest()->getParam('type') == 'publish')
			$criteria->compare('t.publish', 1);
		elseif(Yii::app()->getRequest()->getParam('type') == 'unpublish')
			$criteria->compare('t.publish', 0);
		elseif(Yii::app()->getRequest()->getParam('type') == 'trash')
			$criteria->compare('t.publish', 2);
		else {
			$criteria->addInCondition('t.publish', array(0,1));
			$criteria->compare('t.publish', $this->publish);
		}
		if(Yii::app()->getRequest()->getParam('memberuser'))
			$criteria->compare('t.member_user_id', Yii::app()->getRequest()->getParam('memberuser'));
		else
			$criteria->compare('t.member_user_id', $this->member_user_id);
		if($this->updated_date != null && !in_array($this->updated_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')))
			$criteria->compare('date(t.updated_date)', date('Y-m-d', strtotime($this->updated_date)));
		$criteria->compare('t.updated_id', $this->updated_id);
		
		$criteria->compare('member.profile_id', $this->profile_search);
		if(Yii::app()->getRequest()->getParam('publish'))
			$criteria->compare('member.publish', Yii::app()->getRequest()->getParam('publish'));
		$criteria->compare('member_v.member_name', strtolower($this->member_search), true);
		$criteria->compare('user.level_id', $this->level_search);
		$criteria->compare('user_user.displayname', strtolower($this->user_search), true);
		$criteria->compare('updated.displayname', strtolower($this->updated_search), true);

		if(!Yii::app()->getRequest()->getParam('MemberUserDetail_sort'))
			$criteria->order = 't.id DESC';

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
			//$this->defaultColumns[] = 'id';
			$this->defaultColumns[] = 'publish';
			$this->defaultColumns[] = 'member_user_id';
			$this->defaultColumns[] = 'updated_date';
			$this->defaultColumns[] = 'updated_id';
		}

		return $this->defaultColumns;
	}

	/**
	 * Set default columns to display
	 */
	protected function afterConstruct() {
		if(count($this->defaultColumns) == 0) {
			$this->defaultColumns[] = array(
				'header' => 'No',
				'value' => '$this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize + $row+1'
			);
			if(!Yii::app()->getRequest()->getParam('memberuser')) {
				$this->defaultColumns[] = array(
					'name' => 'profile_search',
					'value' => 'Phrase::trans($data->user->member->profile->profile_name)',
					'filter'=>MemberProfile::getProfile(),
				);
				$this->defaultColumns[] = array(
					'name' => 'member_search',
					'value' => '$data->user->member->view->member_name',
				);
				$this->defaultColumns[] = array(
					'name' => 'level_search',
					'value' => '$data->user->level_id != null ? Phrase::trans($data->user->level->level_name) : \'-\'',
					'filter'=>MemberLevels::getLevel(),
				);
				$this->defaultColumns[] = array(
					'name' => 'user_search',
					'value' => '$data->user->user_id != 0 ? $data->user->user->displayname : "-"',
				);	
			}
			$this->defaultColumns[] = array(
				'name' => 'updated_search',
				'value' => '$data->updated->displayname',
			);
			$this->defaultColumns[] = array(
				'name' => 'updated_date',
				'value' => 'Utility::dateFormat($data->updated_date)',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => $this->filterDatepicker($this, 'updated_date'),
			);
			$this->defaultColumns[] = array(
				'name' => 'publish',
				'value' => '$data->publish == 1 ? CHtml::image(Yii::app()->theme->baseUrl.\'/images/icons/publish.png\') : CHtml::image(Yii::app()->theme->baseUrl.\'/images/icons/unpublish.png\')',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => $this->filterYesNo(),
				'type' => 'raw',
			);
		}
		parent::afterConstruct();
	}

	/**
	 * User get information
	 */
	public static function getInfo($id, $column=null)
	{
		if($column != null) {
			$model = self::model()->findByPk($id, array(
				'select' => $column,
			));
 			if(count(explode(',', $column)) == 1)
 				return $model->$column;
 			else
 				return $model;
			
		} else {
			$model = self::model()->findByPk($id);
			return $model;
		}
	}

}