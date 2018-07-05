<?php
/**
 * ViewMembers
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @created date 7 March 2017, 22:46 WIB
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
 * This is the model class for table "_view_members".
 *
 * The followings are the available columns in table '_view_members':
 * @property string $member_id
 * @property string $member_name
 * @property integer $user_id
 * @property integer $company_id
 * @property string $users
 * @property string $user_all
 * @property string $likes
 * @property string $like_all
 * @property string $views
 * @property string $view_all
 */
class ViewMembers extends CActiveRecord
{
	public $defaultColumns = array();

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ViewMembers the static model class
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
		return '_view_members';
	}

	/**
	 * @return string the primarykey column
	 */
	public function primaryKey()
	{
		return 'member_id';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('member_id, user_id, company_id', 'numerical', 'integerOnly'=>true),
			array('member_id, user_id, company_id', 'length', 'max'=>11),
			array('users, likes, views', 'length', 'max'=>23),
			array('user_all, like_all, view_all', 'length', 'max'=>21),
			array('member_name', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('member_id, member_name, user_id, company_id, users, user_all, likes, like_all, views, view_all', 'safe', 'on'=>'search'),
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
			'user' => array(self::BELONGS_TO, 'Users', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'member_id' => Yii::t('attribute', 'Member'),
			'member_name' => Yii::t('attribute', 'Member Name'),
			'user_id' => Yii::t('attribute', 'User'),
			'company_id' => Yii::t('attribute', 'Company'),
			'users' => Yii::t('attribute', 'Users'),
			'user_all' => Yii::t('attribute', 'User All'),
			'likes' => Yii::t('attribute', 'Likes'),
			'like_all' => Yii::t('attribute', 'Like All'),
			'views' => Yii::t('attribute', 'Views'),
			'view_all' => Yii::t('attribute', 'View All'),
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

		$criteria->compare('t.member_id', $this->member_id);
		$criteria->compare('t.member_name', strtolower($this->member_name), true);
		$criteria->compare('t.user_id', $this->user_id);
		$criteria->compare('t.company_id', $this->company_id);
		$criteria->compare('t.users', $this->users);
		$criteria->compare('t.user_all', $this->user_all);
		$criteria->compare('t.likes', $this->likes);
		$criteria->compare('t.like_all', $this->like_all);
		$criteria->compare('t.views', $this->views);
		$criteria->compare('t.view_all', $this->view_all);

		if(!Yii::app()->getRequest()->getParam('ViewMembers_sort'))
			$criteria->order = 't.member_id DESC';

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
			$this->defaultColumns[] = 'member_id';
			$this->defaultColumns[] = 'member_name';
			$this->defaultColumns[] = 'user_id';
			$this->defaultColumns[] = 'company_id';
			$this->defaultColumns[] = 'users';
			$this->defaultColumns[] = 'user_all';
			$this->defaultColumns[] = 'likes';
			$this->defaultColumns[] = 'like_all';
			$this->defaultColumns[] = 'views';
			$this->defaultColumns[] = 'view_all';
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
			//$this->defaultColumns[] = 'member_id';
			$this->defaultColumns[] = 'member_name';
			$this->defaultColumns[] = 'user_id';
			$this->defaultColumns[] = 'company_id';
			$this->defaultColumns[] = 'users';
			$this->defaultColumns[] = 'user_all';
			$this->defaultColumns[] = 'likes';
			$this->defaultColumns[] = 'like_all';
			$this->defaultColumns[] = 'views';
			$this->defaultColumns[] = 'view_all';
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