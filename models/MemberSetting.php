<?php
/**
 * MemberSetting
 * 
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 5 November 2018, 06:12 WIB
 * @modified date 3 September 2019, 10:41 WIB
 * @link https://github.com/ommu/mod-member
 *
 * This is the model class for table "ommu_member_setting".
 *
 * The followings are the available columns in table "ommu_member_setting":
 * @property integer $id
 * @property string $license
 * @property integer $permission
 * @property string $meta_description
 * @property string $meta_keyword
 * @property integer $personal_profile_id
 * @property integer $company_profile_id
 * @property integer $group_profile_id
 * @property integer $profile_user_limit
 * @property integer $profile_page_user_auto_follow
 * @property string $photo_header_view_size
 * @property integer $photo_limit
 * @property integer $photo_resize
 * @property string $photo_resize_size
 * @property string $photo_view_size
 * @property string $photo_file_type
 * @property integer $friends_auto_follow
 * @property string $modified_date
 * @property integer $modified_id
 *
 * The followings are the available model relations:
 * @property Users $modified
 *
 */

namespace ommu\member\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Json;
use ommu\users\models\Users;

class MemberSetting extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;
	use \ommu\traits\FileTrait;

	public $gridForbiddenColumn = [];

	public $modifiedDisplayname;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_member_setting';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['license', 'permission', 'meta_description', 'meta_keyword', 'profile_user_limit', 'photo_header_view_size', 'photo_limit', 'photo_resize', 'photo_resize_size', 'photo_view_size', 'photo_file_type'], 'required'],
			[['permission', 'profile_user_limit', 'profile_page_user_auto_follow', 'photo_limit', 'photo_resize', 'friends_auto_follow', 'modified_id'], 'integer'],
			[['meta_description', 'meta_keyword'], 'string'],
			[['personal_profile_id', 'company_profile_id', 'group_profile_id'], 'safe'],
			//[['photo_header_view_size', 'photo_resize_size', 'photo_view_size', 'photo_file_type'], 'json'],
			[['license'], 'string', 'max' => 32],
			[['personal_profile_id', 'company_profile_id', 'group_profile_id'], 'string', 'max' => 64],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'license' => Yii::t('app', 'License'),
			'permission' => Yii::t('app', 'Permission'),
			'meta_description' => Yii::t('app', 'Meta Description'),
			'meta_keyword' => Yii::t('app', 'Meta Keyword'),
			'personal_profile_id' => Yii::t('app', 'Personal Profile'),
			'company_profile_id' => Yii::t('app', 'Company Profile'),
			'group_profile_id' => Yii::t('app', 'Group Profile'),
			'profile_user_limit' => Yii::t('app', 'Profile User Limit'),
			'profile_page_user_auto_follow' => Yii::t('app', 'Profile Page User Auto Follow'),
			'photo_header_view_size' => Yii::t('app', 'Photo Header View Size'),
			'photo_limit' => Yii::t('app', 'Photo Limit'),
			'photo_resize' => Yii::t('app', 'Photo Resize'),
			'photo_resize_size' => Yii::t('app', 'Photo Resize Size'),
			'photo_view_size' => Yii::t('app', 'Photo View Size'),
			'photo_view_size[small]' => Yii::t('app', 'Small'),
			'photo_view_size[medium]' => Yii::t('app', 'Medium'),
			'photo_view_size[large]' => Yii::t('app', 'Large'),
			'photo_file_type' => Yii::t('app', 'Photo File Type'),
			'friends_auto_follow' => Yii::t('app', 'Friends Auto Follow'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'modifiedDisplayname' => Yii::t('app', 'Modified'),
			'width' => Yii::t('app', 'Width'),
			'height' => Yii::t('app', 'Height'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getPersonal()
	{
		return $this->hasOne(MemberProfile::className(), ['profile_id' => 'personal_profile_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCompany()
	{
		return $this->hasOne(MemberProfile::className(), ['profile_id' => 'company_profile_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getGroup()
	{
		return $this->hasOne(MemberProfile::className(), ['profile_id' => 'group_profile_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getModified()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'modified_id']);
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
		$this->templateColumns['license'] = [
			'attribute' => 'license',
			'value' => function($model, $key, $index, $column) {
				return $model->license;
			},
		];
		$this->templateColumns['permission'] = [
			'attribute' => 'permission',
			'value' => function($model, $key, $index, $column) {
				return self::getPermission($model->permission);
			},
		];
		$this->templateColumns['meta_description'] = [
			'attribute' => 'meta_description',
			'value' => function($model, $key, $index, $column) {
				return $model->meta_description;
			},
		];
		$this->templateColumns['meta_keyword'] = [
			'attribute' => 'meta_keyword',
			'value' => function($model, $key, $index, $column) {
				return $model->meta_keyword;
			},
		];
		$this->templateColumns['personal_profile_id'] = [
			'attribute' => 'personal_profile_id',
			'value' => function($model, $key, $index, $column) {
				return isset($model->personal) ? $model->personal->profile_name_i : '-';
			},
		];
		$this->templateColumns['company_profile_id'] = [
			'attribute' => 'company_profile_id',
			'value' => function($model, $key, $index, $column) {
				return isset($model->company) ? $model->company->profile_name_i : '-';
			},
		];
		$this->templateColumns['group_profile_id'] = [
			'attribute' => 'group_profile_id',
			'value' => function($model, $key, $index, $column) {
				return isset($model->group) ? $model->group->profile_name_i : '-';
			},
		];
		$this->templateColumns['profile_user_limit'] = [
			'attribute' => 'profile_user_limit',
			'value' => function($model, $key, $index, $column) {
				return $model->profile_user_limit;
			},
		];
		$this->templateColumns['photo_limit'] = [
			'attribute' => 'photo_limit',
			'value' => function($model, $key, $index, $column) {
				return $model->photo_limit;
			},
		];
		$this->templateColumns['photo_resize_size'] = [
			'attribute' => 'photo_resize_size',
			'value' => function($model, $key, $index, $column) {
				return self::getSize($model->photo_resize_size);
			},
		];
		$this->templateColumns['photo_view_size'] = [
			'attribute' => 'photo_view_size',
			'value' => function($model, $key, $index, $column) {
				return self::parsePhotoViewSize($model->photo_view_size);
			},
		];
		$this->templateColumns['photo_header_view_size'] = [
			'attribute' => 'photo_header_view_size',
			'value' => function($model, $key, $index, $column) {
				return self::getSize($model->photo_header_view_size);
			},
		];
		$this->templateColumns['photo_file_type'] = [
			'attribute' => 'photo_file_type',
			'value' => function($model, $key, $index, $column) {
				return $model->photo_file_type;
			},
		];
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
		$this->templateColumns['profile_page_user_auto_follow'] = [
			'attribute' => 'profile_page_user_auto_follow',
			'value' => function($model, $key, $index, $column) {
				return $this->filterYesNo($model->profile_page_user_auto_follow);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class'=>'center'],
		];
		$this->templateColumns['photo_resize'] = [
			'attribute' => 'photo_resize',
			'value' => function($model, $key, $index, $column) {
				return $this->filterYesNo($model->photo_resize);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class'=>'center'],
		];
		$this->templateColumns['friends_auto_follow'] = [
			'attribute' => 'friends_auto_follow',
			'value' => function($model, $key, $index, $column) {
				return $this->filterYesNo($model->friends_auto_follow);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class'=>'center'],
		];
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
			$model = $model->where(['id' => $id])->one();
			return is_array($column) ? $model : $model->$column;
			
		} else {
			$model = self::findOne($id);
			return $model;
		}
	}

	/**
	 * function getPermission
	 */
	public static function getPermission($value=null)
	{
		$items = array(
			1 => Yii::t('app', 'Yes, the public can view "module name" unless they are made private.'),
			0 => Yii::t('app', 'No, the public cannot view "module name".'),
		);

		if($value !== null)
			return $items[$value];
		else
			return $items;
	}

	/**
	 * function getPhotoResize
	 */
	public static function getPhotoResize($value=null)
	{
		$items = array(
			1 => Yii::t('app', 'Yes, resize photo after upload.'),
			0 => Yii::t('app', 'No, not resize photo after upload.'),
		);

		if($value !== null)
			return $items[$value];
		else
			return $items;
	}

	/**
	 * function getSize
	 */
	public static function getSize($sizes)
	{
		if(empty($sizes))
			return '-';

		$width = $sizes['width'] ? $sizes['width'] : '~';
		$height = $sizes['height'] ? $sizes['height'] : '~';

		return $width. 'x' .$height;
	}

	/**
	 * function parsePhotoViewSize
	 */
	public function parsePhotoViewSize($view_size)
	{
		if(empty($view_size))
			return '-';

		$views = [];
		foreach ($view_size as $key => $value) {
			$views[] = ucfirst($key).": ".self::getSize($value);
		}
		return Html::ul($views, ['encode'=>false, 'class'=>'list-boxed']);
	}

	/**
	 * after find attributes
	 */
	public function afterFind()
	{
		parent::afterFind();

		$this->photo_header_view_size = Json::decode($this->photo_header_view_size);
		$this->photo_resize_size = Json::decode($this->photo_resize_size);
		$this->photo_view_size = Json::decode($this->photo_view_size);
		$photo_file_type = Json::decode($this->photo_file_type);
		if(!empty($photo_file_type))
			$this->photo_file_type = $this->formatFileType($photo_file_type, false);
		// $this->modifiedDisplayname = isset($this->modified) ? $this->modified->displayname : '-';
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

	/**
	 * before save attributes
	 */
	public function beforeSave($insert)
	{
		if(parent::beforeSave($insert)) {
			$this->photo_header_view_size = Json::encode($this->photo_header_view_size);
			$this->photo_resize_size = Json::encode($this->photo_resize_size);
			$this->photo_view_size = Json::encode($this->photo_view_size);
			$this->photo_file_type = Json::encode($this->formatFileType($this->photo_file_type));

			// insert new personal profile
			if(!isset($this->personal) && $this->personal_profile_id != '') {
				$model = new MemberProfile();
				$model->profile_name_i = $this->personal_profile_id;
				if($model->save())
					$this->personal_profile_id = $model->profile_id;
			}

			// insert new company profile
			if(!isset($this->company) && $this->company_profile_id != '') {
				$model = new MemberProfile();
				$model->profile_name_i = $this->company_profile_id;
				if($model->save())
					$this->company_profile_id = $model->profile_id;
			}

			// insert new group profile
			if(!isset($this->group) && $this->group_profile_id != '') {
				$model = new MemberProfile();
				$model->profile_name_i = $this->group_profile_id;
				if($model->save())
					$this->group_profile_id = $model->profile_id;
			}
		}
		return true;
	}
}
