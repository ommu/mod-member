<?php
/**
 * MemberProfile
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 2 October 2018, 09:48 WIB
 * @link https://github.com/ommu/mod-member
 *
 * This is the model class for table "ommu_member_profile".
 *
 * The followings are the available columns in table "ommu_member_profile":
 * @property integer $profile_id
 * @property integer $publish
 * @property integer $profile_name
 * @property integer $profile_desc
 * @property string $assignment_roles
 * @property integer $profile_personal
 * @property integer $multiple_user
 * @property integer $user_limit
 * @property string $creation_date
 * @property integer $creation_id
 * @property string $modified_date
 * @property integer $modified_id
 * @property string $updated_date
 *
 * The followings are the available model relations:
 * @property MemberProfileCategory[] $categories
 * @property MemberProfileDocument[] $documents
 * @property Members[] $members
 * @property SourceMessage $title
 * @property SourceMessage $description
 * @property Users $creation
 * @property Users $modified
 *
 */

namespace ommu\member\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\Inflector;
use app\models\SourceMessage;
use ommu\users\models\Users;

class MemberProfile extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;
	use \ommu\traits\FileTrait;

	public $gridForbiddenColumn = ['assignment_roles','modified_date','modified_search','updated_date'];
	public $profile_name_i;
	public $profile_desc_i;

	public $creation_search;
	public $modified_search;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_member_profile';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['profile_name_i', 'profile_desc_i', 'assignment_roles', 'user_limit'], 'required'],
			[['publish', 'profile_name', 'profile_desc', 'profile_personal', 'multiple_user', 'user_limit', 'creation_id', 'modified_id'], 'integer'],
			[['profile_name_i', 'profile_desc_i'], 'string'],
			[['creation_date', 'modified_date', 'updated_date'], 'safe'],
			[['profile_name_i'], 'string', 'max' => 64],
			[['profile_desc_i'], 'string', 'max' => 128],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'profile_id' => Yii::t('app', 'Profile'),
			'publish' => Yii::t('app', 'Publish'),
			'profile_name' => Yii::t('app', 'profile'),
			'profile_desc' => Yii::t('app', 'Description'),
			'assignment_roles' => Yii::t('app', 'Assignment Roles'),
			'profile_personal' => Yii::t('app', 'Personal'),
			'multiple_user' => Yii::t('app', 'Multiple User'),
			'user_limit' => Yii::t('app', 'User Limit'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'profile_name_i' => Yii::t('app', 'profile'),
			'profile_desc_i' => Yii::t('app', 'Description'),
			'creation_search' => Yii::t('app', 'Creation'),
			'modified_search' => Yii::t('app', 'Modified'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCategories()
	{
		return $this->hasMany(MemberProfileCategory::className(), ['profile_id' => 'profile_id'])
			->andOnCondition([sprintf('%s.publish', MemberProfileCategory::tableName()) => 1]);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getDocuments()
	{
		return $this->hasMany(MemberProfileDocument::className(), ['profile_id' => 'profile_id'])
			->andOnCondition([sprintf('%s.publish', MemberProfileDocument::tableName()) => 1]);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getMembers()
	{
		return $this->hasMany(Members::className(), ['profile_id' => 'profile_id'])
			->andOnCondition([sprintf('%s.publish', Members::tableName()) => 1]);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getTitle()
	{
		return $this->hasOne(SourceMessage::className(), ['id' => 'profile_name']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getDescription()
	{
		return $this->hasOne(SourceMessage::className(), ['id' => 'profile_desc']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCreation()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'creation_id']);
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
	 * @return \ommu\member\models\query\MemberProfile the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\member\models\query\MemberProfile(get_called_class());
	}

	/**
	 * Set default columns to display
	 */
	public function init()
	{
		parent::init();

		if(!(Yii::$app instanceof \app\components\Application))
			return;

		$this->templateColumns['_no'] = [
			'header' => Yii::t('app', 'No'),
			'class' => 'yii\grid\SerialColumn',
			'contentOptions' => ['class'=>'center'],
		];
		$this->templateColumns['profile_name_i'] = [
			'attribute' => 'profile_name_i',
			'value' => function($model, $key, $index, $column) {
				return $model->profile_name_i;
			},
		];
		$this->templateColumns['profile_desc_i'] = [
			'attribute' => 'profile_desc_i',
			'value' => function($model, $key, $index, $column) {
				return $model->profile_desc_i;
			},
		];
		$this->templateColumns['assignment_roles'] = [
			'attribute' => 'assignment_roles',
			'value' => function($model, $key, $index, $column) {
				return $this->formatFileType($model->assignment_roles, false);
			},
		];
		$this->templateColumns['user_limit'] = [
			'attribute' => 'user_limit',
			'value' => function($model, $key, $index, $column) {
				return $model->user_limit;
			},
		];
		$this->templateColumns['creation_date'] = [
			'attribute' => 'creation_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->creation_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'creation_date'),
		];
		if(!Yii::$app->request->get('creation')) {
			$this->templateColumns['creation_search'] = [
				'attribute' => 'creation_search',
				'value' => function($model, $key, $index, $column) {
					return isset($model->creation) ? $model->creation->displayname : '-';
					// return $model->creationDisplayname;
				},
			];
		}
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
		$this->templateColumns['updated_date'] = [
			'attribute' => 'updated_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->updated_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'updated_date'),
		];
		$this->templateColumns['profile_personal'] = [
			'attribute' => 'profile_personal',
			'value' => function($model, $key, $index, $column) {
				return $this->filterYesNo($model->profile_personal);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class'=>'center'],
		];
		$this->templateColumns['multiple_user'] = [
			'attribute' => 'multiple_user',
			'value' => function($model, $key, $index, $column) {
				return $this->filterYesNo($model->multiple_user);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class'=>'center'],
		];
		if(!Yii::$app->request->get('trash')) {
			$this->templateColumns['publish'] = [
				'attribute' => 'publish',
				'value' => function($model, $key, $index, $column) {
					$url = Url::to(['publish', 'id'=>$model->primaryKey]);
					return $this->quickAction($url, $model->publish, 'Enable,Disable');
				},
				'filter' => $this->filterYesNo(),
				'contentOptions' => ['class'=>'center'],
				'format' => 'raw',
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
				->where(['profile_id' => $id])
				->one();
			return $model->$column;
			
		} else {
			$model = self::findOne($id);
			return $model;
		}
	}

	/**
	 * function getProfile
	 */
	public static function getProfile($publish=null, $array=true) 
	{
		$model = self::find()->alias('t');
		$model->leftJoin(sprintf('%s title', SourceMessage::tableName()), 't.profile_name=title.id');
		if($publish != null)
			$model->andWhere(['t.publish' => $publish]);

		$model = $model->orderBy('title.message ASC')->all();

		if($array == true)
			return \yii\helpers\ArrayHelper::map($model, 'profile_id', 'profile_name_i');

		return $model;
	}

	/**
	 * after find attributes
	 */
	public function afterFind()
	{
		parent::afterFind();

		$this->profile_name_i = isset($this->title) ? $this->title->message : '';
		$this->profile_desc_i = isset($this->description) ? $this->description->message : '';

		$this->assignment_roles = $this->formatFileType($this->assignment_roles, true, '#');
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate()
	{
		if(parent::beforeValidate()) {
			if($this->isNewRecord) {
				if($this->creation_id == null)
					$this->creation_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
			} else {
				if($this->modified_id == null)
					$this->modified_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
			}
		}
		return true;
	}

	/**
	 * before save attributes
	 */
	public function beforeSave($insert)
	{
		$module = strtolower(Yii::$app->controller->module->id);
		$controller = strtolower(Yii::$app->controller->id);
		$action = strtolower(Yii::$app->controller->action->id);

		$location = Inflector::slug($module.' '.$controller);

		if(parent::beforeSave($insert)) {
			if($insert || (!$insert && !$this->profile_name)) {
				$profile_name = new SourceMessage();
				$profile_name->location = $location.'_title';
				$profile_name->message = $this->profile_name_i;
				if($profile_name->save())
					$this->profile_name = $profile_name->id;

			} else {
				$profile_name = SourceMessage::findOne($this->profile_name);
				$profile_name->message = $this->profile_name_i;
				$profile_name->save();
			}

			if($insert || (!$insert && !$this->profile_desc)) {
				$profile_desc = new SourceMessage();
				$profile_desc->location = $location.'_description';
				$profile_desc->message = $this->profile_desc_i;
				if($profile_desc->save())
					$this->profile_desc = $profile_desc->id;

			} else {
				$profile_desc = SourceMessage::findOne($this->profile_desc);
				$profile_desc->message = $this->profile_desc_i;
				$profile_desc->save();
			}

			$this->assignment_roles = $this->formatFileType($this->assignment_roles, false, '#');
		}
		return true;
	}
}
