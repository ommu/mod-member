<?php
/**
 * MemberDocumentType
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 OMMU (www.ommu.id)
 * @created date 2 October 2018, 11:04 WIB
 * @modified date 3 September 2019, 21:14 WIB
 * @link https://github.com/ommu/mod-member
 *
 * This is the model class for table "ommu_member_document_type".
 *
 * The followings are the available columns in table "ommu_member_document_type":
 * @property integer $document_id
 * @property integer $publish
 * @property integer $document_name
 * @property integer $document_desc
 * @property string $creation_date
 * @property integer $creation_id
 * @property string $modified_date
 * @property integer $modified_id
 * @property string $updated_date
 *
 * The followings are the available model relations:
 * @property MemberProfileDocument[] $documents
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
use app\models\Users;

class MemberDocumentType extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;

	public $gridForbiddenColumn = ['document_desc_i', 'creation_date', 'creationDisplayname', 'modified_date', 'modifiedDisplayname', 'updated_date'];

	public $document_name_i;
	public $document_desc_i;
	public $creationDisplayname;
	public $modifiedDisplayname;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_member_document_type';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['document_name_i'], 'required'],
			[['publish', 'document_name', 'document_desc', 'creation_id', 'modified_id'], 'integer'],
			[['document_name_i', 'document_desc_i'], 'string'],
			[['document_desc_i'], 'safe'],
			[['document_name_i'], 'string', 'max' => 64],
			[['document_desc_i'], 'string', 'max' => 128],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'document_id' => Yii::t('app', 'Document'),
			'publish' => Yii::t('app', 'Publish'),
			'document_name' => Yii::t('app', 'Document'),
			'document_desc' => Yii::t('app', 'Description'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'document_name_i' => Yii::t('app', 'Document'),
			'document_desc_i' => Yii::t('app', 'Description'),
			'documents' => Yii::t('app', 'Documents'),
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
			return $this->hasMany(MemberProfileDocument::className(), ['document_id' => 'document_id'])
			->alias('documents')
			->andOnCondition([sprintf('%s.publish', 'documents') => $publish]);

		$model = MemberProfileDocument::find()
			->alias('t')
			->where(['document_id' => $this->document_id]);
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
	public function getTitle()
	{
		return $this->hasOne(SourceMessage::className(), ['id' => 'document_name']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getDescription()
	{
		return $this->hasOne(SourceMessage::className(), ['id' => 'document_desc']);
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
	 * @return \ommu\member\models\query\MemberDocumentType the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\member\models\query\MemberDocumentType(get_called_class());
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
		$this->templateColumns['document_name_i'] = [
			'attribute' => 'document_name_i',
			'value' => function($model, $key, $index, $column) {
				return $model->document_name_i;
			},
		];
		$this->templateColumns['document_desc_i'] = [
			'attribute' => 'document_desc_i',
			'value' => function($model, $key, $index, $column) {
				return $model->document_desc_i;
			},
		];
		$this->templateColumns['documents'] = [
			'attribute' => 'documents',
			'value' => function($model, $key, $index, $column) {
				$documents = $model->getDocuments(true);
				return Html::a($documents, ['setting/profile/document/manage', 'document'=>$model->primaryKey, 'publish'=>1], ['title'=>Yii::t('app', '{count} documents', ['count'=>$documents]), 'data-pjax'=>0]);
			},
			'filter' => false,
			'contentOptions' => ['class'=>'text-center'],
			'format' => 'raw',
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
			$model = $model->where(['document_id' => $id])->one();
			return is_array($column) ? $model : $model->$column;
			
		} else {
			$model = self::findOne($id);
			return $model;
		}
	}

	/**
	 * function getType
	 */
	public static function getType($publish=null, $array=true) 
	{
		$model = self::find()
			->alias('t')
			->select(['t.document_id', 't.document_name']);
		$model->leftJoin(sprintf('%s title', SourceMessage::tableName()), 't.document_name=title.id');
		if($publish != null)
			$model->andWhere(['t.publish' => $publish]);

		$model = $model->orderBy('title.message ASC')->all();

		if($array == true)
			return \yii\helpers\ArrayHelper::map($model, 'document_id', 'document_name_i');

		return $model;
	}

	/**
	 * after find attributes
	 */
	public function afterFind()
	{
		parent::afterFind();

		$this->document_name_i = isset($this->title) ? $this->title->message : '';
		$this->document_desc_i = isset($this->description) ? $this->description->message : '';
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
		$module = strtolower(Yii::$app->controller->module->id);
		$controller = strtolower(Yii::$app->controller->id);
		$action = strtolower(Yii::$app->controller->action->id);

		$location = Inflector::slug($module.' '.$controller);

		if(parent::beforeSave($insert)) {
			if($insert || (!$insert && !$this->document_name)) {
				$document_name = new SourceMessage();
				$document_name->location = $location.'_title';
				$document_name->message = $this->document_name_i;
				if($document_name->save())
					$this->document_name = $document_name->id;

			} else {
				$document_name = SourceMessage::findOne($this->document_name);
				$document_name->message = $this->document_name_i;
				$document_name->save();
			}

			if($insert || (!$insert && !$this->document_desc)) {
				$document_desc = new SourceMessage();
				$document_desc->location = $location.'_description';
				$document_desc->message = $this->document_desc_i;
				if($document_desc->save())
					$this->document_desc = $document_desc->id;

			} else {
				$document_desc = SourceMessage::findOne($this->document_desc);
				$document_desc->message = $this->document_desc_i;
				$document_desc->save();
			}

		}
		return true;
	}
}
