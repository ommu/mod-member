<?php
/**
 * MemberCompanyContact
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 OMMU (www.ommu.id)
 * @created date 1 November 2018, 19:36 WIB
 * @link https://github.com/ommu/mod-member
 *
 * This is the model class for table "ommu_member_company_contact".
 *
 * The followings are the available columns in table "ommu_member_company_contact":
 * @property integer $id
 * @property integer $publish
 * @property integer $status
 * @property integer $member_company_id
 * @property integer $contact_cat_id
 * @property string $contact_value
 * @property string $verified_date
 * @property integer $verified_id
 * @property string $creation_date
 * @property integer $creation_id
 * @property string $modified_date
 * @property integer $modified_id
 * @property string $updated_date
 *
 * The followings are the available model relations:
 * @property MemberContactCategory $category
 * @property MemberCompany $memberCompany
 * @property Users $creation
 * @property Users $modified
 *
 */

namespace ommu\member\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Users;

class MemberCompanyContact extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;

	public $gridForbiddenColumn = ['verified_search','creation_date','creationDisplayname','modified_date', 'modifiedDisplayname', 'updated_date'];
	public $old_status_i;

	public $verified_search;
	public $creationDisplayname;
	public $modifiedDisplayname;
	public $profile_search;
	public $member_search;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_member_company_contact';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['member_company_id', 'contact_cat_id', 'contact_value'], 'required'],
			[['publish', 'status', 'member_company_id', 'contact_cat_id', 'verified_id', 'creation_id', 'modified_id'], 'integer'],
			[['contact_value'], 'string'],
			[['verified_date', 'creation_date', 'modified_date', 'updated_date'], 'safe'],
			[['contact_cat_id'], 'exist', 'skipOnError' => true, 'targetClass' => MemberContactCategory::className(), 'targetAttribute' => ['contact_cat_id' => 'cat_id']],
			[['member_company_id'], 'exist', 'skipOnError' => true, 'targetClass' => MemberCompany::className(), 'targetAttribute' => ['member_company_id' => 'id']],
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
			'status' => Yii::t('app', 'Status'),
			'member_company_id' => Yii::t('app', 'Member Company'),
			'contact_cat_id' => Yii::t('app', 'Category'),
			'contact_value' => Yii::t('app', 'Contact'),
			'verified_date' => Yii::t('app', 'Verified Date'),
			'verified_id' => Yii::t('app', 'Verified'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'creationDisplayname' => Yii::t('app', 'Creation'),
			'verified_search' => Yii::t('app', 'Verified'),
			'modifiedDisplayname' => Yii::t('app', 'Modified'),
			'profile_search' => Yii::t('app', 'Profile'),
			'member_search' => Yii::t('app', 'Member'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCategory()
	{
		return $this->hasOne(MemberContactCategory::className(), ['cat_id' => 'contact_cat_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getMemberCompany()
	{
		return $this->hasOne(MemberCompany::className(), ['id' => 'member_company_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getVerified()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'verified_id']);
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
	 * @return \ommu\member\models\query\MemberCompanyContact the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\member\models\query\MemberCompanyContact(get_called_class());
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
		$this->templateColumns['member_search'] = [
			'attribute' => 'member_search',
			'value' => function($model, $key, $index, $column) {
				return isset($model->memberCompany) ? $model->memberCompany->member->displayname : '-';
			},
			'visible' => !Yii::$app->request->get('member') && !Yii::$app->request->get('company') ? true : false,
		];
		$this->templateColumns['profile_search'] = [
			'attribute' => 'profile_search',
			'value' => function($model, $key, $index, $column) {
				return isset($model->memberCompany) ? $model->memberCompany->member->profile->title->message : '-';
			},
			'filter' => MemberProfile::getProfile(),
			'visible' => !Yii::$app->request->get('member') && !Yii::$app->request->get('company') ? true : false,
		];
		$this->templateColumns['contact_cat_id'] = [
			'attribute' => 'contact_cat_id',
			'value' => function($model, $key, $index, $column) {
				return isset($model->category) ? $model->category->title->message : '-';
			},
			'filter' => MemberContactCategory::getCategory(),
			'visible' => !Yii::$app->request->get('category') ? true : false,
		];
		$this->templateColumns['contact_value'] = [
			'attribute' => 'contact_value',
			'value' => function($model, $key, $index, $column) {
				return $model->contact_value;
			},
		];
		$this->templateColumns['verified_date'] = [
			'attribute' => 'verified_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->verified_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'verified_date'),
		];
		$this->templateColumns['verified_search'] = [
			'attribute' => 'verified_search',
			'value' => function($model, $key, $index, $column) {
				return isset($model->verified) ? $model->verified->displayname : '-';
			},
			'visible' => !Yii::$app->request->get('verified') ? true : false,
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
		$this->templateColumns['status'] = [
			'attribute' => 'status',
			'value' => function($model, $key, $index, $column) {
				$url = Url::to(['status', 'id'=>$model->primaryKey]);
				return $this->quickAction($url, $model->status, 'Verified,Unverified');
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class'=>'text-center'],
			'format' => 'raw',
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

		$this->old_status_i = $this->status;
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate()
	{
		$action = strtolower(Yii::$app->controller->action->id);

		if(parent::beforeValidate()) {
			if($action == 'status' && $this->old_status_i != $this->status)
				$this->verified_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;

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
