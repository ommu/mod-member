<?php
/**
 * MemberProfileDocument
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 2 October 2018, 11:34 WIB
 * @modified date 30 October 2018, 11:01 WIB
 * @link https://github.com/ommu/mod-member
 *
 * This is the model class for table "ommu_member_profile_document".
 *
 * The followings are the available columns in table "ommu_member_profile_document":
 * @property integer $id
 * @property integer $publish
 * @property integer $profile_id
 * @property integer $document_id
 * @property integer $required
 * @property string $creation_date
 * @property integer $creation_id
 * @property string $modified_date
 * @property integer $modified_id
 * @property string $updated_date
 *
 * The followings are the available model relations:
 * @property MemberDocuments[] $documents
 * @property MemberDocumentType $document
 * @property MemberProfile $profile
 * @property Users $creation
 * @property Users $modified
 *
 */

namespace ommu\member\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use ommu\users\models\Users;

class MemberProfileDocument extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;

	public $gridForbiddenColumn = ['modified_date','modified_search','updated_date'];
	public $document_name_i;
	public $document_desc_i;

	public $creation_search;
	public $modified_search;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_member_profile_document';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['profile_id', 'document_id'], 'required'],
			[['publish', 'profile_id', 'document_id', 'required', 'creation_id', 'modified_id'], 'integer'],
			[['creation_date', 'modified_date', 'updated_date'], 'safe'],
			[['document_id'], 'exist', 'skipOnError' => true, 'targetClass' => MemberDocumentType::className(), 'targetAttribute' => ['document_id' => 'document_id']],
			[['profile_id'], 'exist', 'skipOnError' => true, 'targetClass' => MemberProfile::className(), 'targetAttribute' => ['profile_id' => 'profile_id']],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'publish' => Yii::t('app', 'Publish'),
			'profile_id' => Yii::t('app', 'Profile'),
			'document_id' => Yii::t('app', 'Document'),
			'required' => Yii::t('app', 'Required'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'creation_search' => Yii::t('app', 'Creation'),
			'modified_search' => Yii::t('app', 'Modified'),
			'document_name_i' => Yii::t('app', 'Document Name'),
			'document_desc_i' => Yii::t('app', 'Document Desc'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getDocuments()
	{
		return $this->hasMany(MemberDocuments::className(), ['profile_document_id' => 'id'])
			->andOnCondition([sprintf('%s.publish', MemberDocuments::tableName()) => 1]);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getDocument()
	{
		return $this->hasOne(MemberDocumentType::className(), ['document_id' => 'document_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getProfile()
	{
		return $this->hasOne(MemberProfile::className(), ['profile_id' => 'profile_id']);
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
	 * @return \ommu\member\models\query\MemberProfileDocument the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\member\models\query\MemberProfileDocument(get_called_class());
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
		if(!Yii::$app->request->get('profile')) {
			$this->templateColumns['profile_id'] = [
				'attribute' => 'profile_id',
				'value' => function($model, $key, $index, $column) {
					return isset($model->profile) ? $model->profile->title->message : '-';
				},
				'filter' => MemberProfile::getProfile(),
			];
		}
		if(!Yii::$app->request->get('document')) {
			$this->templateColumns['document_id'] = [
				'attribute' => 'document_id',
				'value' => function($model, $key, $index, $column) {
					return isset($model->document->title) ? $model->document->title->message : '-';
				},
				'filter' => MemberDocumentType::getType(),
			];
		}
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
		$this->templateColumns['required'] = [
			'attribute' => 'required',
			'value' => function($model, $key, $index, $column) {
				return $this->filterYesNo($model->required);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class'=>'center'],
		];
		if(!Yii::$app->request->get('trash')) {
			$this->templateColumns['publish'] = [
				'attribute' => 'publish',
				'filter' => $this->filterYesNo(),
				'value' => function($model, $key, $index, $column) {
					$url = Url::to(['publish', 'id'=>$model->primaryKey]);
					return $this->quickAction($url, $model->publish);
				},
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
				->where(['id' => $id])
				->one();
			return $model->$column;
			
		} else {
			$model = self::findOne($id);
			return $model;
		}
	}

	/**
	 * after find attributes
	 */
	public function afterFind()
	{
		parent::afterFind();

		$this->document_name_i = isset($this->document) ? $this->document->document_name_i : '';
		$this->document_desc_i = isset($this->document) ? $this->document->document_desc_i : '';
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
}
