<?php
/**
 * MemberLikes
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2012 Ommu Platform (www.ommu.co)
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
 * This is the model class for table "ommu_member_likes".
 *
 * The followings are the available columns in table 'ommu_member_likes':
 * @property string $like_id
 * @property integer $publish
 * @property string $member_id
 * @property string $user_id
 * @property string $likes_date
 * @property string $likes_ip
 * @property string $updated_date
 *
 * The followings are the available model relations:
 * @property Members $member
 */
class MemberLikes extends CActiveRecord
{
	use GridViewTrait;

	public $defaultColumns = array();
	
	// Variable Search
	public $profile_search;
	public $member_search;
	public $user_search;
	public $like_search;
	public $unlike_search;

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return MemberLikes the static model class
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
		return 'ommu_member_likes';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('publish, member_id, user_id', 'required'),
			array('publish', 'numerical', 'integerOnly'=>true),
			array('member_id, user_id', 'length', 'max'=>11),
			array('likes_ip', 'length', 'max'=>20),
			array('', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('like_id, publish, member_id, user_id, likes_date, likes_ip, updated_date,
				profile_search, member_search, user_search, like_search, unlike_search', 'safe', 'on'=>'search'),
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
			'view' => array(self::BELONGS_TO, 'ViewMemberLikes', 'like_id'),
			'member' => array(self::BELONGS_TO, 'Members', 'member_id'),
			'user' => array(self::BELONGS_TO, 'Users', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'like_id' => Yii::t('attribute', 'Like'),
			'publish' => Yii::t('attribute', 'Publish'),
			'member_id' => Yii::t('attribute', 'Member'),
			'user_id' => Yii::t('attribute', 'User'),
			'likes_date' => Yii::t('attribute', 'Likes Date'),
			'likes_ip' => Yii::t('attribute', 'Likes Ip'),
			'updated_date' => Yii::t('attribute', 'Updated Date'),
			'profile_search' => Yii::t('attribute', 'Profile'),
			'member_search' => Yii::t('attribute', 'Member'),
			'user_search' => Yii::t('attribute', 'User'),
			'like_search' => Yii::t('attribute', 'Like'),
			'unlike_search' => Yii::t('attribute', 'Unlike'),
		);
	}
	
	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;
		
		// Custom Search
		$criteria->with = array(
			'view' => array(
				'alias' => 'view',
			),
			'member' => array(
				'alias' => 'member',
				'select' => 'publish, profile_id'
			),
			'member.view' => array(
				'alias' => 'member_v',
				'select' => 'member_name'
			),
			'user' => array(
				'alias' => 'user',
				'select' => 'displayname',
			),
		);

		$criteria->compare('t.like_id', $this->like_id);
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
		if(Yii::app()->getRequest()->getParam('member'))
			$criteria->compare('t.member_id', Yii::app()->getRequest()->getParam('member'));
		else
			$criteria->compare('t.member_id', $this->member_id);
		if(Yii::app()->getRequest()->getParam('user'))
			$criteria->compare('t.user_id',Yii::app()->getRequest()->getParam('user'));
		else
			$criteria->compare('t.user_id', $this->user_id);
		if($this->likes_date != null && !in_array($this->likes_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')))
			$criteria->compare('date(t.likes_date)', date('Y-m-d', strtotime($this->likes_date)));
		$criteria->compare('t.likes_ip', strtolower($this->likes_ip), true);
		if($this->updated_date != null && !in_array($this->updated_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')))
			$criteria->compare('date(t.updated_date)', date('Y-m-d', strtotime($this->updated_date)));

		$criteria->compare('member.profile_id', $this->profile_search);
		if(Yii::app()->getRequest()->getParam('publish'))
			$criteria->compare('member.publish', Yii::app()->getRequest()->getParam('publish'));
		$criteria->compare('member_v.member_name', strtolower($this->member_search), true);
		$criteria->compare('user.displayname', strtolower($this->user_search), true);
		$criteria->compare('view.likes', $this->like_search);
		$criteria->compare('view.unlikes', $this->unlike_search);

		if(!Yii::app()->getRequest()->getParam('MemberLikes_sort'))
			$criteria->order = 't.like_id DESC';

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
			//$this->defaultColumns[] = 'like_id';
			$this->defaultColumns[] = 'publish';
			$this->defaultColumns[] = 'member_id';
			$this->defaultColumns[] = 'user_id';
			$this->defaultColumns[] = 'likes_date';
			$this->defaultColumns[] = 'likes_ip';
			$this->defaultColumns[] = 'updated_date';
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
			if(!Yii::app()->getRequest()->getParam('member')) {
				$this->defaultColumns[] = array(
					'name' => 'profile_search',
					'value' => 'Phrase::trans($data->member->profile->profile_name)',
					'filter' =>MemberProfile::getProfile(),
				);
				$this->defaultColumns[] = array(
					'name' => 'member_search',
					'value' => '$data->member->view->member_name',
				);
			}
			if(!Yii::app()->getRequest()->getParam('user')) {
				$this->defaultColumns[] = array(
					'name' => 'user_search',
					'value' => '$data->user->displayname',
				);
			}
			$this->defaultColumns[] = array(
				'name' => 'likes_date',
				'value' => 'Yii::app()->dateFormatter->formatDateTime($data->likes_date, \'medium\', false)',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => $this->filterDatepicker($this, 'likes_date'),
			);
			$this->defaultColumns[] = array(
				'name' => 'likes_ip',
				'value' => '$data->likes_ip',
				'htmlOptions' => array(
					'class' => 'center',
				),
			);
			/*
			$this->defaultColumns[] = array(
				'name' => 'updated_date',
				'value' => 'Yii::app()->dateFormatter->formatDateTime($data->updated_date, \'medium\', false)',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => $this->filterDatepicker($this, 'updated_date'),
			);
			*/
			$this->defaultColumns[] = array(
				'name' => 'like_search',
				'value' => 'CHtml::link($data->view->likes != \'0\' ? $data->view->likes : \'0\', Yii::app()->controller->createUrl("o/likedetail/manage", array(\'like\'=>$data->like_id,\'type\'=>\'publish\')))',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'type' => 'raw',
			);
			$this->defaultColumns[] = array(
				'name' => 'unlike_search',
				'value' => 'CHtml::link($data->view->unlikes != \'0\' ? $data->view->unlikes : \'0\', Yii::app()->controller->createUrl("o/likedetail/manage", array(\'like\'=>$data->like_id,\'type\'=>\'unpublish\')))',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'type' => 'raw',
			);
			if(!Yii::app()->getRequest()->getParam('type')) {
				$this->defaultColumns[] = array(
					'name' => 'publish',
					'value' => 'Utility::getPublish(Yii::app()->controller->createUrl(\'publish\', array(\'id\'=>$data->like_id)), $data->publish, 1)',
					'htmlOptions' => array(
						'class' => 'center',
					),
					'filter' => $this->filterYesNo(),
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

	/**
	 * before validate attributes
	 */
	protected function beforeValidate() {
		if(parent::beforeValidate()) {
			if($this->isNewRecord)
				$this->user_id = !Yii::app()->user->isGuest ? Yii::app()->user->id : null;
				
			$this->likes_ip = $_SERVER['REMOTE_ADDR'];
		}
		return true;
	}
}