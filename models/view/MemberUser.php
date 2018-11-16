<?php
/**
 * MemberUser
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 16 November 2018, 11:29 WIB
 * @link https://github.com/ommu/mod-users
 *
 * This is the model class for table "_member_user".
 *
 * The followings are the available columns in table "_member_user":
 * @property integer $member_id
 * @property integer $profile_id
 * @property integer $user_id
 *
 * The followings are the available model relations:
 * @property Members $member
 *
 */

namespace ommu\member\models\view;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use ommu\member\models\Members;
use ommu\users\models\Users;

class MemberUser extends \app\components\ActiveRecord
{
	public $gridForbiddenColumn = [];

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return '_member_user';
	}

	/**
	 * @return string the primarykey column
	 */
	public static function primaryKey()
	{
		return ['member_id'];
	}

	/**
	 * @return \yii\db\Connection the database connection used by this AR class.
	 */
	public static function getDb()
	{
		return Yii::$app->get('ecc4');
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['member_id', 'user_id'], 'required'],
			[['member_id', 'profile_id', 'user_id'], 'integer'],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'member_id' => Yii::t('app', 'Member'),
			'profile_id' => Yii::t('app', 'Profile'),
			'user_id' => Yii::t('app', 'User'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getMember()
	{
		return $this->hasOne(Members::className(), ['member_id' => 'member_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getUser()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'user_id']);
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
		$this->templateColumns['member_id'] = [
			'attribute' => 'member_id',
			'value' => function($model, $key, $index, $column) {
				return $model->member_id;
			},
		];
		$this->templateColumns['profile_id'] = [
			'attribute' => 'profile_id',
			'value' => function($model, $key, $index, $column) {
				return $model->profile_id;
			},
		];
		$this->templateColumns['user_id'] = [
			'attribute' => 'user_id',
			'value' => function($model, $key, $index, $column) {
				return $model->user_id;
			},
		];
	}

	/**
	 * User get information
	 */
	public static function getInfo($id, $column=null)
	{
		if($column != null) {
			$model = self::find()
				->select([$column])
				->where(['member_id' => $id])
				->one();
			return $model->$column;
			
		} else {
			$model = self::findOne($id);
			return $model;
		}
	}
}
