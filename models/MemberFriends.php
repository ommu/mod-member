<?php
/**
 * MemberFriends
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 31 October 2018, 05:27 WIB
 * @link https://github.com/ommu/mod-member
 *
 * This is the model class for table "ommu_member_friends".
 *
 * The followings are the available columns in table "ommu_member_friends":
 * @property integer $id
 * @property integer $type_id
 * @property integer $user_id
 * @property integer $request_id
 * @property string $request_date
 * @property string $modified_date
 * @property integer $modified_id
 *
 * The followings are the available model relations:
 * @property MemberFriendHistory[] $histories
 * @property MemberFriendType $type
 * @property Users $user
 * @property Users $request
 * @property Users $modified
 *
 */

namespace ommu\member\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use ommu\users\models\Users;

class MemberFriends extends \app\components\ActiveRecord
{
	public $gridForbiddenColumn = ['modified_date','modified_search'];

	public $user_search;
	public $request_search;
	public $modified_search;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_member_friends';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['type_id', 'user_id', 'request_id'], 'required'],
			[['type_id', 'user_id', 'request_id', 'modified_id'], 'integer'],
			[['request_date', 'modified_date'], 'safe'],
			[['type_id'], 'exist', 'skipOnError' => true, 'targetClass' => MemberFriendType::className(), 'targetAttribute' => ['type_id' => 'id']],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'type_id' => Yii::t('app', 'Type'),
			'user_id' => Yii::t('app', 'User'),
			'request_id' => Yii::t('app', 'Request'),
			'request_date' => Yii::t('app', 'Request Date'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'user_search' => Yii::t('app', 'User'),
			'request_search' => Yii::t('app', 'Request'),
			'modified_search' => Yii::t('app', 'Modified'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getHistories()
	{
		return $this->hasMany(MemberFriendHistory::className(), ['friend_id' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getType()
	{
		return $this->hasOne(MemberFriendType::className(), ['id' => 'type_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getUser()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'user_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getRequest()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'request_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getModified()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'modified_id']);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\member\models\query\MemberFriends the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\member\models\query\MemberFriends(get_called_class());
	}

	/**
	 * Set default columns to display
	 */
	public function init() 
	{
		parent::init();

		$this->templateColumns['_no'] = [
			'header' => Yii::t('app', 'No'),
			'class'  => 'yii\grid\SerialColumn',
			'contentOptions' => ['class'=>'center'],
		];
		if(!Yii::$app->request->get('type')) {
			$this->templateColumns['type_id'] = [
				'attribute' => 'type_id',
				'value' => function($model, $key, $index, $column) {
					return isset($model->type) ? $model->type->title->message : '-';
				},
				'filter' => MemberFriendType::getType(),
			];
		}
		if(!Yii::$app->request->get('user')) {
			$this->templateColumns['user_search'] = [
				'attribute' => 'user_search',
				'value' => function($model, $key, $index, $column) {
					return isset($model->user) ? $model->user->displayname : '-';
				},
			];
		}
		if(!Yii::$app->request->get('request')) {
			$this->templateColumns['request_search'] = [
				'attribute' => 'request_search',
				'value' => function($model, $key, $index, $column) {
					return isset($model->request) ? $model->request->displayname : '-';
				},
			];
		}
		$this->templateColumns['request_date'] = [
			'attribute' => 'request_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->request_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'request_date'),
		];
		$this->templateColumns['modified_date'] = [
			'attribute' => 'modified_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->modified_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'modified_date'),
		];
		if(!Yii::$app->request->get('modified')) {
			$this->templateColumns['modified_search'] = [
				'attribute' => 'modified_search',
				'value' => function($model, $key, $index, $column) {
					return isset($model->modified) ? $model->modified->displayname : '-';
					// return $model->modifiedDisplayname;
				},
			];
		}
	}

	/**
	 * User get information
	 */
	public static function getInfo($id, $column=null)
	{
		if($column != null) {
			$model = self::find()
				->select([$column])
				->where(['id' => $id])
				->one();
			return $model->$column;
			
		} else {
			$model = self::findOne($id);
			return $model;
		}
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate()
	{
		if(parent::beforeValidate()) {
			if(!$this->isNewRecord) {
				if($this->modified_id == null)
					$this->modified_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
			}
		}
		return true;
	}
}
