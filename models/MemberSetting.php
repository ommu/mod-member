<?php
/**
 * MemberSetting
 * 
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 5 November 2018, 06:12 WIB
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
 * @property string $form_custom_insert_field
 * @property integer $level_member_default
 * @property integer $profile_user_limit
 * @property integer $profile_page_user_auto_follow
 * @property string $profile_views
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
use yii\helpers\Url;
use ommu\users\models\Users;

class MemberSetting extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;
	use \ommu\traits\FileTrait;

	public $gridForbiddenColumn = [];

	public $modified_search;

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
			[['license', 'permission', 'meta_description', 'meta_keyword', 'form_custom_insert_field', 'profile_user_limit', 'profile_views', 'photo_header_view_size', 'photo_limit', 'photo_resize', 'photo_resize_size', 'photo_view_size', 'photo_file_type'], 'required'],
			[['permission', 'level_member_default', 'profile_user_limit', 'profile_page_user_auto_follow', 'photo_limit', 'photo_resize', 'friends_auto_follow', 'modified_id'], 'integer'],
			[['meta_description', 'meta_keyword'], 'string'],
			//[['form_custom_insert_field', 'profile_views', 'photo_header_view_size', 'photo_resize_size', 'photo_view_size', 'photo_file_type'], 'serialize'],
			[['modified_date'], 'safe'],
			[['license'], 'string', 'max' => 32],
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
			'form_custom_insert_field' => Yii::t('app', 'Form Custom Insert Field'),
			'level_member_default' => Yii::t('app', 'Level Member Default'),
			'profile_user_limit' => Yii::t('app', 'Profile User Limit'),
			'profile_page_user_auto_follow' => Yii::t('app', 'Profile Page User Auto Follow'),
			'profile_views' => Yii::t('app', 'Profile Views'),
			'photo_header_view_size' => Yii::t('app', 'Photo Header View Size'),
			'photo_limit' => Yii::t('app', 'Photo Limit'),
			'photo_resize' => Yii::t('app', 'Photo Resize'),
			'photo_resize_size' => Yii::t('app', 'Photo Resize Size'),
			'photo_view_size' => Yii::t('app', 'Photo View Size'),
			'photo_file_type' => Yii::t('app', 'Photo File Type'),
			'friends_auto_follow' => Yii::t('app', 'Friends Auto Follow'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'modified_search' => Yii::t('app', 'Modified'),
			'photo_header_view_size[i]' => Yii::t('app', 'Photo Header View Size'),
			'photo_header_view_size[width]' => Yii::t('app', 'Width'),
			'photo_header_view_size[height]' => Yii::t('app', 'Height'),
			'photo_resize_size[i]' => Yii::t('app', 'Photo Resize Size'),
			'photo_resize_size[width]' => Yii::t('app', 'Width'),
			'photo_resize_size[height]' => Yii::t('app', 'Height'),
			'photo_view_size[i]' => Yii::t('app', 'Photo View Size'),
			'photo_view_size[width]' => Yii::t('app', 'Width'),
			'photo_view_size[height]' => Yii::t('app', 'Height'),
		];
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
	 * @return \ommu\member\models\query\MemberSetting the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\member\models\query\MemberSetting(get_called_class());
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
		$this->templateColumns['form_custom_insert_field'] = [
			'attribute' => 'form_custom_insert_field',
			'value' => function($model, $key, $index, $column) {
				return serialize($model->form_custom_insert_field);
			},
		];
		$this->templateColumns['level_member_default'] = [
			'attribute' => 'level_member_default',
			'value' => function($model, $key, $index, $column) {
				return $model->level_member_default;
			},
		];
		$this->templateColumns['profile_user_limit'] = [
			'attribute' => 'profile_user_limit',
			'value' => function($model, $key, $index, $column) {
				return $model->profile_user_limit;
			},
		];
		$this->templateColumns['profile_views'] = [
			'attribute' => 'profile_views',
			'value' => function($model, $key, $index, $column) {
				return serialize($model->profile_views);
			},
		];
		$this->templateColumns['photo_header_view_size'] = [
			'attribute' => 'photo_header_view_size',
			'value' => function($model, $key, $index, $column) {
				return self::getSize($model->photo_header_view_size);
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
				return self::getSize($model->photo_view_size);
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
			$this->templateColumns['modified_search'] = [
				'attribute' => 'modified_search',
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
	 * after find attributes
	 */
	public function afterFind()
	{
		parent::afterFind();

		$this->form_custom_insert_field = unserialize($this->form_custom_insert_field);
		$this->profile_views = unserialize($this->profile_views);
		$this->photo_header_view_size = unserialize($this->photo_header_view_size);
		$this->photo_resize_size = unserialize($this->photo_resize_size);
		$this->photo_view_size = unserialize($this->photo_view_size);
		$photo_file_type = unserialize($this->photo_file_type);
		if(!empty($photo_file_type))
			$this->photo_file_type = $this->formatFileType($photo_file_type, false);
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
			$this->form_custom_insert_field = serialize($this->form_custom_insert_field);
			$this->profile_views = serialize($this->profile_views);
			$this->photo_header_view_size = serialize($this->photo_header_view_size);
			$this->photo_resize_size = serialize($this->photo_resize_size);
			$this->photo_view_size = serialize($this->photo_view_size);
			$this->photo_file_type = serialize($this->formatFileType($this->photo_file_type));
		}
		return true;
	}
}
