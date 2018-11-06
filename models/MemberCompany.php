<?php
/**
 * MemberCompany
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 31 October 2018, 15:29 WIB
 * @link https://github.com/ommu/mod-member
 *
 * This is the model class for table "ommu_member_company".
 *
 * The followings are the available columns in table "ommu_member_company":
 * @property integer $id
 * @property integer $member_id
 * @property integer $company_id
 * @property integer $company_type_id
 * @property integer $company_cat_id
 * @property string $info_intro
 * @property string $info_article
 * @property string $company_address
 * @property integer $company_country_id
 * @property integer $company_province_id
 * @property integer $company_city_id
 * @property string $company_district
 * @property string $company_village
 * @property integer $company_zipcode
 * @property string $creation_date
 * @property integer $creation_id
 * @property string $modified_date
 * @property integer $modified_id
 * @property string $updated_date
 *
 * The followings are the available model relations:
 * @property Members $member
 * @property IpediaCompanies $company
 * @property MemberCompanyType $companyType
 * @property MemberProfileCategory $companyCategory
 * @property MemberCompanyContact[] $contacts
 * @property Users $creation
 * @property Users $modified
 *
 */

namespace ommu\member\models;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use ommu\users\models\Users;
use app\modules\ipedia\models\IpediaCompany as IpediaCompanies;

class MemberCompany extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;

	public $gridForbiddenColumn = ['info_intro','info_article','creation_date','creation_search','modified_date','modified_search','updated_date'];
	public $member_i;

	// Variable Search
	public $creation_search;
	public $modified_search;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_member_company';
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
			[['member_id', 'company_id', 'company_type_id', 'company_cat_id', 'info_intro', 'info_article', 'company_address', 'company_country_id', 'company_province_id', 'company_city_id', 'company_district', 'company_village', 'company_zipcode', 'member_i'], 'required'],
			[['member_id', 'company_id', 'company_type_id', 'company_cat_id', 'company_country_id', 'company_province_id', 'company_city_id', 'company_zipcode', 'creation_id', 'modified_id'], 'integer'],
			[['info_intro', 'info_article', 'company_address'], 'string'],
			[['creation_date', 'modified_date', 'updated_date'], 'safe'],
			[['company_district', 'company_village', 'member_i'], 'string', 'max' => 64],
			[['member_id'], 'exist', 'skipOnError' => true, 'targetClass' => Members::className(), 'targetAttribute' => ['member_id' => 'member_id']],
			[['company_id'], 'exist', 'skipOnError' => true, 'targetClass' => IpediaCompanies::className(), 'targetAttribute' => ['company_id' => 'company_id']],
			[['company_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => MemberCompanyType::className(), 'targetAttribute' => ['company_type_id' => 'type_id']],
			[['company_cat_id'], 'exist', 'skipOnError' => true, 'targetClass' => MemberProfileCategory::className(), 'targetAttribute' => ['company_cat_id' => 'cat_id']],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'member_id' => Yii::t('app', 'Company'),
			'company_id' => Yii::t('app', 'Company'),
			'company_type_id' => Yii::t('app', 'Type'),
			'company_cat_id' => Yii::t('app', 'Category'),
			'info_intro' => Yii::t('app', 'Info Intro'),
			'info_article' => Yii::t('app', 'Info Article'),
			'company_address' => Yii::t('app', 'Company Address'),
			'company_country_id' => Yii::t('app', 'Company Country'),
			'company_province_id' => Yii::t('app', 'Company Province'),
			'company_city_id' => Yii::t('app', 'Company City'),
			'company_district' => Yii::t('app', 'Company District'),
			'company_village' => Yii::t('app', 'Company Village'),
			'company_zipcode' => Yii::t('app', 'Company Zipcode'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'member_i' => Yii::t('app', 'Company'),
			'creation_search' => Yii::t('app', 'Creation'),
			'modified_search' => Yii::t('app', 'Modified'),
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
	public function getCompany()
	{
		return $this->hasOne(IpediaCompanies::className(), ['company_id' => 'company_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCompanyType()
	{
		return $this->hasOne(MemberCompanyType::className(), ['type_id' => 'company_type_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCompanyCategory()
	{
		return $this->hasOne(MemberProfileCategory::className(), ['cat_id' => 'company_cat_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getContacts()
	{
		return $this->hasMany(MemberCompanyContact::className(), ['member_company_id' => 'id'])
			->andOnCondition(['publish' => 1]);
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
	 * @return \ommu\member\models\query\MemberCompany the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\member\models\query\MemberCompany(get_called_class());
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
			$this->templateColumns['member_i'] = [
				'attribute' => 'member_i',
				'value' => function($model, $key, $index, $column) {
					return $model->member_i;
				},
			];
		}
		if(!Yii::$app->request->get('companyType')) {
			$this->templateColumns['company_type_id'] = [
				'attribute' => 'company_type_id',
				'value' => function($model, $key, $index, $column) {
					return isset($model->companyType) ? $model->companyType->title->message : '-';
				},
				'filter' => MemberCompanyType::getType(),
			];
		}
		if(!Yii::$app->request->get('companyCategory')) {
			$this->templateColumns['company_cat_id'] = [
				'attribute' => 'company_cat_id',
				'value' => function($model, $key, $index, $column) {
					return isset($model->companyCategory) ? $model->companyCategory->title->message : '-';
				},
				'filter' => MemberProfileCategory::getCategory(),
			];
		}
		$this->templateColumns['info_intro'] = [
			'attribute' => 'info_intro',
			'value' => function($model, $key, $index, $column) {
				return $model->info_intro;
			},
		];
		$this->templateColumns['info_article'] = [
			'attribute' => 'info_article',
			'value' => function($model, $key, $index, $column) {
				return $model->info_article;
			},
		];
		$this->templateColumns['company_address'] = [
			'attribute' => 'company_address',
			'value' => function($model, $key, $index, $column) {
				return $model->company_address;
			},
		];
		$this->templateColumns['company_village'] = [
			'attribute' => 'company_village',
			'value' => function($model, $key, $index, $column) {
				return $model->company_village;
			},
		];
		$this->templateColumns['company_district'] = [
			'attribute' => 'company_district',
			'value' => function($model, $key, $index, $column) {
				return $model->company_district;
			},
		];
		$this->templateColumns['company_city_id'] = [
			'attribute' => 'company_city_id',
			'value' => function($model, $key, $index, $column) {
				return $model->company_city_id;
			},
		];
		$this->templateColumns['company_province_id'] = [
			'attribute' => 'company_province_id',
			'value' => function($model, $key, $index, $column) {
				return $model->company_province_id;
			},
		];
		$this->templateColumns['company_country_id'] = [
			'attribute' => 'company_country_id',
			'value' => function($model, $key, $index, $column) {
				return $model->company_country_id;
			},
		];
		$this->templateColumns['company_zipcode'] = [
			'attribute' => 'company_zipcode',
			'value' => function($model, $key, $index, $column) {
				return $model->company_zipcode;
			},
		];
		$this->templateColumns['creation_date'] = [
			'attribute' => 'creation_date',
			'value' => function($model, $key, $index, $column) {
				return !in_array($model->creation_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00']) ? Yii::$app->formatter->format($model->creation_date, 'datetime') : '-';
			},
			'filter' => $this->filterDatepicker($this, 'creation_date'),
			'format' => 'html',
		];
		if(!Yii::$app->request->get('creation')) {
			$this->templateColumns['creation_search'] = [
				'attribute' => 'creation_search',
				'value' => function($model, $key, $index, $column) {
					return isset($model->creation) ? $model->creation->displayname : '-';
				},
			];
		}
		$this->templateColumns['modified_date'] = [
			'attribute' => 'modified_date',
			'value' => function($model, $key, $index, $column) {
				return !in_array($model->modified_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00']) ? Yii::$app->formatter->format($model->modified_date, 'datetime') : '-';
			},
			'filter' => $this->filterDatepicker($this, 'modified_date'),
			'format' => 'html',
		];
		if(!Yii::$app->request->get('modified')) {
			$this->templateColumns['modified_search'] = [
				'attribute' => 'modified_search',
				'value' => function($model, $key, $index, $column) {
					return isset($model->modified) ? $model->modified->displayname : '-';
				},
			];
		}
		$this->templateColumns['updated_date'] = [
			'attribute' => 'updated_date',
			'value' => function($model, $key, $index, $column) {
				return !in_array($model->updated_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00']) ? Yii::$app->formatter->format($model->updated_date, 'datetime') : '-';
			},
			'filter' => $this->filterDatepicker($this, 'updated_date'),
			'format' => 'html',
		];
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
		$this->member_i = $this->member->displayname;
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate()
	{
		if(parent::beforeValidate()) {
			if($this->isNewRecord)
				$this->creation_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
			else
				$this->modified_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
		}
		return true;
	}

	/**
	 * After save attributes
	 */
	public function afterSave($insert, $changedAttributes)
	{
		parent::afterSave($insert, $changedAttributes);

		// update member displayname
		if(!$insert) {
			$member = Members::findOne($this->member_id);
			$member->displayname = $this->member_i;
			$member->update();
		}
	}
}
