<?php
/**
 * MemberProfile
 * 
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 2 October 2018, 09:48 WIB
 * @modified date 2 September 2019, 18:27 WIB
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
use yii\helpers\Json;
use yii\helpers\Inflector;
use app\models\SourceMessage;
use ommu\users\models\Users;

class MemberProfile extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;

	public $gridForbiddenColumn = ['profile_desc_i', 'assignment_roles', 'creation_date', 'creationDisplayname', 'modified_date', 'modifiedDisplayname', 'updated_date'];

	public $profile_name_i;
	public $profile_desc_i;
	public $creationDisplayname;
	public $modifiedDisplayname;

	const SCENARIO_UPADTE = 'updateForm';

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
			[['profile_name_i'], 'required'],
			[['profile_desc_i', 'assignment_roles', 'user_limit'], 'required', 'on' => self::SCENARIO_UPADTE],
			[['publish', 'profile_name', 'profile_desc', 'profile_personal', 'multiple_user', 'user_limit', 'creation_id', 'modified_id'], 'integer'],
			[['profile_name_i', 'profile_desc_i'], 'string'],
			[['profile_desc_i', 'assignment_roles', 'profile_personal', 'multiple_user', 'user_limit'], 'safe'],
			//[['assignment_roles'], 'json'],
			[['profile_name_i'], 'string', 'max' => 64],
			[['profile_desc_i'], 'string', 'max' => 128],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function scenarios()
	{
		$scenarios = parent::scenarios();
		$scenarios[self::SCENARIO_UPADTE] = ['profile_name_i', 'profile_desc_i', 'assignment_roles', 'profile_personal', 'multiple_user', 'user_limit'];
		return $scenarios;
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'profile_id' => Yii::t('app', 'Profile'),
			'publish' => Yii::t('app', 'Publish'),
			'profile_name' => Yii::t('app', 'Profile'),
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
			'profile_name_i' => Yii::t('app', 'Profile'),
			'profile_desc_i' => Yii::t('app', 'Description'),
			'categories' => Yii::t('app', 'Categories'),
			'documents' => Yii::t('app', 'Documents'),
			'members' => Yii::t('app', 'Members'),
			'creationDisplayname' => Yii::t('app', 'Creation'),
			'modifiedDisplayname' => Yii::t('app', 'Modified'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCategories($count=false, $publish=1)
	{
		if($count == false)
			return $this->hasMany(MemberProfileCategory::className(), ['profile_id' => 'profile_id'])
			->alias('categories')
			->andOnCondition([sprintf('%s.publish', 'categories') => $publish]);

		$model = MemberProfileCategory::find()
			->alias('t')
			->where(['profile_id' => $this->profile_id]);
		if($publish == 0)
			$model->unpublish();
		elseif($publish == 1)
			$model->published();
		elseif($publish == 2)
			$model->deleted();
		$categories = $model->count();

		return $categories ? $categories : 0;
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getDocuments($count=false, $publish=1)
	{
		if($count == false)
			return $this->hasMany(MemberProfileDocument::className(), ['profile_id' => 'profile_id'])
			->alias('documents')
			->andOnCondition([sprintf('%s.publish', 'documents') => $publish]);

		$model = MemberProfileDocument::find()
			->alias('t')
			->where(['profile_id' => $this->profile_id]);
		if($publish == 0)
			$model->unpublish();
		elseif($publish == 1)
			$model->published();
		elseif($publish == 2)
			$model->deleted();
		$documents = $model->count();

		return $documents ? $documents : 0;
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getMembers($count=false, $publish=1)
	{
		if($count == false)
			return $this->hasMany(Members::className(), ['profile_id' => 'profile_id'])
			->alias('members')
			->andOnCondition([sprintf('%s.publish', 'members') => $publish]);

		$model = Members::find()
			->alias('t')
			->where(['profile_id' => $this->profile_id]);
		if($publish == 0)
			$model->unpublish();
		elseif($publish == 1)
			$model->published();
		elseif($publish == 2)
			$model->deleted();
		$members = $model->count();

		return $members ? $members : 0;
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
	 */
	public function getMultipleUserLimit()
	{
		$limit = MemberSetting::getInfo('profile_user_limit');

		return $limit ? $limit : 20;
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
			'header' => '#',
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
				return self::parseAssignmentRoles($model->assignment_roles);
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
			$this->templateColumns['creationDisplayname'] = [
				'attribute' => 'creationDisplayname',
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
			$this->templateColumns['modifiedDisplayname'] = [
				'attribute' => 'modifiedDisplayname',
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
		$this->templateColumns['categories'] = [
			'attribute' => 'categories',
			'value' => function($model, $key, $index, $column) {
				$categories = $model->getCategories(true);
				return Html::a($categories, ['setting/profile/category/manage', 'profile'=>$model->primaryKey, 'publish'=>1], ['title'=>Yii::t('app', '{count} categories', ['count'=>$categories]), 'data-pjax'=>0]);
			},
			'filter' => false,
			'contentOptions' => ['class'=>'center'],
			'format' => 'raw',
		];
		$this->templateColumns['documents'] = [
			'attribute' => 'documents',
			'value' => function($model, $key, $index, $column) {
				$documents = $model->getDocuments(true);
				return Html::a($documents, ['setting/profile/document/manage', 'profile'=>$model->primaryKey, 'publish'=>1], ['title'=>Yii::t('app', '{count} documents', ['count'=>$documents]), 'data-pjax'=>0]);
			},
			'filter' => false,
			'contentOptions' => ['class'=>'center'],
			'format' => 'raw',
		];
		$this->templateColumns['members'] = [
			'attribute' => 'members',
			'value' => function($model, $key, $index, $column) {
				$members = $model->getMembers(true);
				return Html::a($members, ['admin/manage', 'profile'=>$model->primaryKey, 'publish'=>1], ['title'=>Yii::t('app', '{count} members', ['count'=>$members]), 'data-pjax'=>0]);
			},
			'filter' => false,
			'contentOptions' => ['class'=>'center'],
			'format' => 'raw',
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
			'label' => Yii::t('app', 'Multiple'),
			'value' => function($model, $key, $index, $column) {
				return $this->filterYesNo($model->multiple_user);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class'=>'center'],
		];
		$this->templateColumns['user_limit'] = [
			'attribute' => 'user_limit',
			'label' => Yii::t('app', 'Limit'),
			'value' => function($model, $key, $index, $column) {
				return $model->user_limit;
			},
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
			$model = self::find();
			if(is_array($column))
				$model->select($column);
			else
				$model->select([$column]);
			$model = $model->where(['profile_id' => $id])->one();
			return is_array($column) ? $model : $model->$column;
			
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
		$model = self::find()
			->alias('t')
			->select(['t.profile_id', 't.profile_name']);
		$model->leftJoin(sprintf('%s title', SourceMessage::tableName()), 't.profile_name=title.id');
		if($publish != null)
			$model->andWhere(['t.publish' => $publish]);

		$model = $model->orderBy('title.message ASC')->all();

		if($array == true)
			return \yii\helpers\ArrayHelper::map($model, 'profile_id', 'profile_name_i');

		return $model;
	}

	/**
	 * function parseAssignmentRoles
	 */
	public static function parseAssignmentRoles($assignmentRoles, $sep='li')
	{
		if(!is_array($assignmentRoles) || (is_array($assignmentRoles) && empty($assignmentRoles)))
			return '-';

		if($sep == 'li') {
			return Html::ul($assignmentRoles, ['item' => function($item, $index) {
				return Html::tag('li', $item);
			}, 'class'=>'list-boxed']);
		}

		return implode($sep, $answer);
	}

	/**
	 * after find attributes
	 */
	public function afterFind()
	{
		parent::afterFind();

		$this->profile_name_i = isset($this->title) ? $this->title->message : '';
		$this->profile_desc_i = isset($this->description) ? $this->description->message : '';
		$this->assignment_roles = Json::decode($this->assignment_roles);
		// $this->creationDisplayname = isset($this->creation) ? $this->creation->displayname : '-';
		// $this->modifiedDisplayname = isset($this->modified) ? $this->modified->displayname : '-';
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

			if($this->profile_personal) {
				$this->multiple_user = 0;
				$this->user_limit = 1;
			} else {
				if(!$this->multiple_user)
					$this->user_limit = 1;
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

			$this->assignment_roles = Json::encode($this->assignment_roles);
		}
		return true;
	}
}
