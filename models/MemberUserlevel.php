<?php
/**
 * MemberUserlevel
 * 
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 2 October 2018, 09:24 WIB
 * @link https://github.com/ommu/mod-member
 *
 * This is the model class for table "ommu_member_userlevel".
 *
 * The followings are the available columns in table "ommu_member_userlevel":
 * @property integer $level_id
 * @property integer $publish
 * @property integer $default
 * @property integer $level_name
 * @property integer $level_desc
 * @property string $creation_date
 * @property integer $creation_id
 * @property string $modified_date
 * @property integer $modified_id
 * @property string $updated_date
 *
 * The followings are the available model relations:
 * @property MemberUser[] $users
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

class MemberUserlevel extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;

	public $gridForbiddenColumn = ['modified_date', 'modifiedDisplayname', 'updated_date'];
	public $level_name_i;
	public $level_desc_i;

	public $creationDisplayname;
	public $modifiedDisplayname;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_member_userlevel';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['level_name_i', 'level_desc_i'], 'required'],
			[['publish', 'default', 'level_name', 'level_desc', 'creation_id', 'modified_id'], 'integer'],
			[['level_name_i', 'level_desc_i'], 'string'],
			[['level_name_i'], 'string', 'max' => 64],
			[['level_desc_i'], 'string', 'max' => 128],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'level_id' => Yii::t('app', 'Level'),
			'publish' => Yii::t('app', 'Publish'),
			'default' => Yii::t('app', 'Default'),
			'level_name' => Yii::t('app', 'Level Name'),
			'level_desc' => Yii::t('app', 'Level Desc'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'level_name_i' => Yii::t('app', 'Level Name'),
			'level_desc_i' => Yii::t('app', 'Level Desc'),
			'creationDisplayname' => Yii::t('app', 'Creation'),
			'modifiedDisplayname' => Yii::t('app', 'Modified'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getUsers()
	{
		return $this->hasMany(MemberUser::className(), ['level_id' => 'level_id'])
			->alias('users')
			->andOnCondition([sprintf('%s.publish', 'users') => 1]);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getTitle()
	{
		return $this->hasOne(SourceMessage::className(), ['id' => 'level_name']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getDescription()
	{
		return $this->hasOne(SourceMessage::className(), ['id' => 'level_desc']);
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
	 * @return \ommu\member\models\query\MemberUserlevel the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\member\models\query\MemberUserlevel(get_called_class());
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
		$this->templateColumns['level_name_i'] = [
			'attribute' => 'level_name_i',
			'value' => function($model, $key, $index, $column) {
				return $model->level_name_i;
			},
		];
		$this->templateColumns['level_desc_i'] = [
			'attribute' => 'level_desc_i',
			'value' => function($model, $key, $index, $column) {
				return$model->level_desc_i;
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
		$this->templateColumns['default'] = [
			'attribute' => 'default',
			'value' => function($model, $key, $index, $column) {
				return $this->filterYesNo($model->default);
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
			$model = self::find();
			if(is_array($column))
				$model->select($column);
			else
				$model->select([$column]);
			$model = $model->where(['level_id' => $id])->one();
			return is_array($column) ? $model : $model->$column;
			
		} else {
			$model = self::findOne($id);
			return $model;
		}
	}

	/**
	 * function getDefault
	 */
	public static function getDefault() 
	{
		$model = self::find()
			->select(['level_id'])
			->where(['default' => 1])
			->one();
			
		return $model->level_id;
	}

	/**
	 * function getUserlevel
	 */
	public static function getUserlevel($publish=null, $array=true) 
	{
		$model = self::find()->alias('t');
		$model->leftJoin(sprintf('%s title', SourceMessage::tableName()), 't.level_name=title.id');
		if($publish != null)
			$model->andWhere(['t.publish' => $publish]);

		$model = $model->orderBy('title.message ASC')->all();

		if($array == true)
			return \yii\helpers\ArrayHelper::map($model, 'level_id', 'level_name_i');

		return $model;
	}

	/**
	 * after find attributes
	 */
	public function afterFind()
	{
		parent::afterFind();

		$this->level_name_i = isset($this->title) ? $this->title->message : '';
		$this->level_desc_i = isset($this->description) ? $this->description->message : '';
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
			if($insert || (!$insert && !$this->level_name)) {
				$level_name = new SourceMessage();
				$level_name->location = $location.'_title';
				$level_name->message = $this->level_name_i;
				if($level_name->save())
					$this->level_name = $level_name->id;

			} else {
				$level_name = SourceMessage::findOne($this->level_name);
				$level_name->message = $this->level_name_i;
				$level_name->save();
			}

			if($insert || (!$insert && !$this->level_desc)) {
				$level_desc = new SourceMessage();
				$level_desc->location = $location.'_description';
				$level_desc->message = $this->level_desc_i;
				if($level_desc->save())
					$this->level_desc = $level_desc->id;

			} else {
				$level_desc = SourceMessage::findOne($this->level_desc);
				$level_desc->message = $this->level_desc_i;
				$level_desc->save();
			}

			// set to default
			if($this->default == 1) {
				self::model()->updateAll(array(
					'default' => 0,
				));
				$this->default = 1;
			}
		}
		return true;
	}
}
