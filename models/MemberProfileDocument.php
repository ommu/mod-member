<?php
/**
 * MemberProfileDocument
 * 
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 2 October 2018, 11:34 WIB
 * @modified date 2 September 2019, 18:27 WIB
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

	public $gridForbiddenColumn = ['creation_date', 'creationDisplayname', 'modified_date', 'modifiedDisplayname', 'updated_date'];

	public $profileName;
	public $documentName;
	public $creationDisplayname;
	public $modifiedDisplayname;

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
			[['publish', 'profile_id', 'required', 'creation_id', 'modified_id'], 'integer'],
			[['document_id'], 'string', 'max' => 64],
			// [['document_id'], 'exist', 'skipOnError' => true, 'targetClass' => MemberDocumentType::className(), 'targetAttribute' => ['document_id' => 'document_id']],
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
			'documents' => Yii::t('app', 'Documents'),
			'profileName' => Yii::t('app', 'Profile'),
			'documentName' => Yii::t('app', 'Document'),
			'creationDisplayname' => Yii::t('app', 'Creation'),
			'modifiedDisplayname' => Yii::t('app', 'Modified'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getDocuments($count=false, $publish=1)
	{
		if($count == false)
			return $this->hasMany(MemberDocuments::className(), ['profile_document_id' => 'id'])
			->alias('documents')
			->andOnCondition([sprintf('%s.publish', 'documents') => $publish]);

		$model = MemberDocuments::find()
			->alias('t')
			->where(['profile_document_id' => $this->id]);
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

		if(!(Yii::$app instanceof \app\components\Application))
			return;

		if(!$this->hasMethod('search'))
			return;

		$this->templateColumns['_no'] = [
			'header' => '#',
			'class' => 'app\components\grid\SerialColumn',
			'contentOptions' => ['class'=>'text-center'],
		];
		$this->templateColumns['profile_id'] = [
			'attribute' => 'profile_id',
			'value' => function($model, $key, $index, $column) {
				return isset($model->profile) ? $model->profile->title->message : '-';
				// return $model->profileName;
			},
			'filter' => MemberProfile::getProfile(),
			'visible' => !Yii::$app->request->get('profile') ? true : false,
		];
		$this->templateColumns['document_id'] = [
			'attribute' => 'document_id',
			'value' => function($model, $key, $index, $column) {
				return isset($model->document) ? $model->document->title->message : '-';
				// return $model->documentName;
			},
			'filter' => MemberDocumentType::getType(),
			'visible' => !Yii::$app->request->get('document') ? true : false,
		];
		$this->templateColumns['creation_date'] = [
			'attribute' => 'creation_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->creation_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'creation_date'),
		];
		$this->templateColumns['creationDisplayname'] = [
			'attribute' => 'creationDisplayname',
			'value' => function($model, $key, $index, $column) {
				return isset($model->creation) ? $model->creation->displayname : '-';
				// return $model->creationDisplayname;
			},
			'visible' => !Yii::$app->request->get('creation') ? true : false,
		];
		$this->templateColumns['modified_date'] = [
			'attribute' => 'modified_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->modified_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'modified_date'),
		];
		$this->templateColumns['modifiedDisplayname'] = [
			'attribute' => 'modifiedDisplayname',
			'value' => function($model, $key, $index, $column) {
				return isset($model->modified) ? $model->modified->displayname : '-';
				// return $model->modifiedDisplayname;
			},
			'visible' => !Yii::$app->request->get('modified') ? true : false,
		];
		$this->templateColumns['updated_date'] = [
			'attribute' => 'updated_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->updated_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'updated_date'),
		];
		$this->templateColumns['documents'] = [
			'attribute' => 'documents',
			'value' => function($model, $key, $index, $column) {
				$documents = $model->getDocuments(true);
				return Html::a($documents, ['document/manage', 'profileDocument'=>$model->primaryKey, 'publish'=>1], ['title'=>Yii::t('app', '{count} documents', ['count'=>$documents]), 'data-pjax'=>0]);
			},
			'filter' => false,
			'contentOptions' => ['class'=>'text-center'],
			'format' => 'raw',
		];
		$this->templateColumns['required'] = [
			'attribute' => 'required',
			'value' => function($model, $key, $index, $column) {
				return $this->filterYesNo($model->required);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class'=>'text-center'],
		];
		$this->templateColumns['publish'] = [
			'attribute' => 'publish',
			'value' => function($model, $key, $index, $column) {
				$url = Url::to(['publish', 'id'=>$model->primaryKey]);
				return $this->quickAction($url, $model->publish);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class'=>'text-center'],
			'format' => 'raw',
			'visible' => !Yii::$app->request->get('trash') ? true : false,
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
	 * after find attributes
	 */
	public function afterFind()
	{
		parent::afterFind();

		// $this->profileName = isset($this->profile) ? $this->profile->title->message : '-';
		// $this->documentName = isset($this->document) ? $this->document->title->message : '-';
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
		}
		return true;
	}

	/**
	 * before save attributes
	 */
	public function beforeSave($insert)
	{
		if(parent::beforeSave($insert)) {
			// insert document type
			if(!isset($this->document)) {
				$model = new MemberDocumentType();
				$model->document_name_i = $this->document_id;
				if($model->save())
					$this->document_id = $model->document_id;
			}
		}

		return true;
	}
}
