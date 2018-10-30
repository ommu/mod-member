<?php
/**
 * MemberHistoryUsername
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 30 October 2018, 23:04 WIB
 * @modified date 30 October 2018, 23:44 WIB
 * @link https://github.com/ommu/mod-member
 *
 * This is the model class for table "ommu_member_history_username".
 *
 * The followings are the available columns in table "ommu_member_history_username":
 * @property integer $id
 * @property integer $member_id
 * @property string $username
 * @property string $updated_date
 * @property integer $updated_id
 *
 * The followings are the available model relations:
 * @property Members $member
 * @property Users $updated
 *
 */

namespace ommu\member\models;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use ommu\users\models\Users;

class MemberHistoryUsername extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;

	public $gridForbiddenColumn = [];

	// Variable Search
	public $member_search;
	public $updated_search;
	public $profile_search;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_member_history_username';
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
			[['member_id', 'username', 'updated_id'], 'required'],
			[['member_id', 'updated_id'], 'integer'],
			[['updated_date'], 'safe'],
			[['username'], 'string', 'max' => 32],
			[['member_id'], 'exist', 'skipOnError' => true, 'targetClass' => Members::className(), 'targetAttribute' => ['member_id' => 'member_id']],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'member_id' => Yii::t('app', 'Member'),
			'username' => Yii::t('app', 'Old Username'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'updated_id' => Yii::t('app', 'Updated'),
			'member_search' => Yii::t('app', 'Member'),
			'updated_search' => Yii::t('app', 'Updated'),
			'profile_search' => Yii::t('app', 'Profile'),
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
	public function getUpdated()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'updated_id']);
	}

	/**
	 * @inheritdoc
	 * @return \ommu\member\models\query\MemberHistoryUsername the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\member\models\query\MemberHistoryUsername(get_called_class());
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
		if(!Yii::$app->request->get('member')) {
			$this->templateColumns['profile_search'] = [
				'attribute' => 'profile_search',
				'value' => function($model, $key, $index, $column) {
					return isset($model->member) ? $model->member->profile_id : '-';
				},
				'filter' => MemberProfile::getProfile(),
			];
			$this->templateColumns['member_search'] = [
				'attribute' => 'member_search',
				'value' => function($model, $key, $index, $column) {
					return isset($model->member) ? $model->member->displayname : '-';
				},
			];
		}
		$this->templateColumns['username'] = [
			'attribute' => 'username',
			'value' => function($model, $key, $index, $column) {
				return $model->username;
			},
		];
		$this->templateColumns['updated_date'] = [
			'attribute' => 'updated_date',
			'value' => function($model, $key, $index, $column) {
				return !in_array($model->updated_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00']) ? Yii::$app->formatter->format($model->updated_date, 'datetime') : '-';
			},
			'filter' => $this->filterDatepicker($this, 'updated_date'),
			'format' => 'html',
		];
		if(!Yii::$app->request->get('updated')) {
			$this->templateColumns['updated_search'] = [
				'attribute' => 'updated_search',
				'value' => function($model, $key, $index, $column) {
					return isset($model->updated) ? $model->updated->displayname : '-';
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
			if(!$this->isNewRecord)
				$this->updated_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
		}
		return true;
	}
}
