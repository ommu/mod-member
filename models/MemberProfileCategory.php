<?php
/**
 * MemberProfileCategory
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 OMMU (www.ommu.id)
 * @created date 2 October 2018, 09:57 WIB
 * @modified date 2 September 2019, 18:27 WIB
 * @link https://github.com/ommu/mod-member
 *
 * This is the model class for table "ommu_member_profile_category".
 *
 * The followings are the available columns in table "ommu_member_profile_category":
 * @property integer $cat_id
 * @property integer $publish
 * @property integer $profile_id
 * @property integer $parent_id
 * @property integer $cat_name
 * @property integer $cat_desc
 * @property string $creation_date
 * @property integer $creation_id
 * @property string $modified_date
 * @property integer $modified_id
 * @property string $updated_date
 *
 * The followings are the available model relations:
 * @property MemberCompany[] $companies
 * @property MemberProfile $profile
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

class MemberProfileCategory extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;

	public $gridForbiddenColumn = ['cat_desc_i', 'creation_date', 'creationDisplayname', 'modified_date', 'modifiedDisplayname', 'updated_date'];

	public $cat_name_i;
	public $cat_desc_i;
	public $profileName;
	public $creationDisplayname;
	public $modifiedDisplayname;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_member_profile_category';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['profile_id', 'cat_name_i'], 'required'],
			[['publish', 'profile_id', 'cat_name', 'cat_desc', 'creation_id', 'modified_id'], 'integer'],
			[['cat_name_i', 'cat_desc_i'], 'string'],
			[['parent_id', 'cat_desc_i'], 'safe'],
			[['parent_id', 'cat_name_i'], 'string', 'max' => 64],
			[['cat_desc_i'], 'string', 'max' => 128],
			[['profile_id'], 'exist', 'skipOnError' => true, 'targetClass' => MemberProfile::className(), 'targetAttribute' => ['profile_id' => 'profile_id']],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'cat_id' => Yii::t('app', 'Category'),
			'publish' => Yii::t('app', 'Publish'),
			'profile_id' => Yii::t('app', 'Profile'),
			'parent_id' => Yii::t('app', 'Parent'),
			'cat_name' => Yii::t('app', 'Category'),
			'cat_desc' => Yii::t('app', 'Descrioption'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'cat_name_i' => Yii::t('app', 'Category'),
			'cat_desc_i' => Yii::t('app', 'Descrioption'),
			'companies' => Yii::t('app', 'Companies'),
			'profileName' => Yii::t('app', 'Profile'),
			'creationDisplayname' => Yii::t('app', 'Creation'),
			'modifiedDisplayname' => Yii::t('app', 'Modified'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCompanies($count=false)
	{
        if ($count == false) {
            return $this->hasMany(MemberCompany::className(), ['company_cat_id' => 'cat_id']);
        }

		$model = MemberCompany::find()
            ->alias('t')
            ->where(['company_cat_id' => $this->cat_id]);
		$companies = $model->count();

		return $companies ? $companies : 0;
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
	public function getParent()
	{
		return $this->hasOne(self::className(), ['cat_id' => 'parent_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getTitle()
	{
		return $this->hasOne(SourceMessage::className(), ['id' => 'cat_name']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getDescription()
	{
		return $this->hasOne(SourceMessage::className(), ['id' => 'cat_desc']);
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
	 * @return \ommu\member\models\query\MemberProfileCategory the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\member\models\query\MemberProfileCategory(get_called_class());
	}

	/**
	 * Set default columns to display
	 */
	public function init()
	{
        parent::init();

        if (!(Yii::$app instanceof \app\components\Application)) {
            return;
        }

        if (!$this->hasMethod('search')) {
            return;
        }

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
		$this->templateColumns['parent_id'] = [
			'attribute' => 'parent_id',
			'value' => function($model, $key, $index, $column) {
				return isset($model->parent) ? $model->parent->title->message : '-';
			},
			'filter' => false,
			//'filter' => self::getCategory(),
		];
		$this->templateColumns['cat_name_i'] = [
			'attribute' => 'cat_name_i',
			'value' => function($model, $key, $index, $column) {
				return $model->cat_name_i;
			},
		];
		$this->templateColumns['cat_desc_i'] = [
			'attribute' => 'cat_desc_i',
			'value' => function($model, $key, $index, $column) {
				return $model->cat_desc_i;
			},
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
		$this->templateColumns['companies'] = [
			'attribute' => 'companies',
			'value' => function($model, $key, $index, $column) {
				$companies = $model->getCompanies(true);
				return Html::a($companies, ['company/manage', 'companyCat'=>$model->primaryKey], ['title'=>Yii::t('app', '{count} companies', ['count'=>$companies]), 'data-pjax'=>0]);
			},
			'filter' => false,
			'contentOptions' => ['class'=>'text-center'],
			'format' => 'raw',
		];
		$this->templateColumns['publish'] = [
			'attribute' => 'publish',
			'value' => function($model, $key, $index, $column) {
				$url = Url::to(['publish', 'id'=>$model->primaryKey]);
				return $this->quickAction($url, $model->publish, 'Enable,Disable');
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
        if ($column != null) {
            $model = self::find();
            if (is_array($column)) {
                $model->select($column);
            } else {
                $model->select([$column]);
            }
            $model = $model->where(['cat_id' => $id])->one();
            return is_array($column) ? $model : $model->$column;

        } else {
            $model = self::findOne($id);
            return $model;
        }
	}

	/**
	 * function getCategory
	 */
	public static function getCategory($profile=null, $publish=null, $array=true)
	{
		$model = self::find()
            ->alias('t')
			->select(['t.cat_id', 't.cat_name']);
		$model->leftJoin(sprintf('%s title', SourceMessage::tableName()), 't.cat_name=title.id');
        if ($publish != null) {
            $model->andWhere(['t.publish' => $publish]);
        }
        if ($profile != null) {
            $model->andWhere(['t.profile_id' => $profile]);
        }

		$model = $model->orderBy('title.message ASC')->all();

        if ($array == true) {
            return \yii\helpers\ArrayHelper::map($model, 'cat_id', 'cat_name_i');
        }

		return $model;
	}

	/**
	 * after find attributes
	 */
	public function afterFind()
	{
		parent::afterFind();

		$this->cat_name_i = isset($this->title) ? $this->title->message : '';
		$this->cat_desc_i = isset($this->description) ? $this->description->message : '';
		// $this->profileName = isset($this->profile) ? $this->profile->title->message : '-';
		// $this->creationDisplayname = isset($this->creation) ? $this->creation->displayname : '-';
		// $this->modifiedDisplayname = isset($this->modified) ? $this->modified->displayname : '-';
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate()
	{
        if (parent::beforeValidate()) {
            if ($this->isNewRecord) {
                if ($this->creation_id == null) {
                    $this->creation_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
                }
            } else {
                if ($this->modified_id == null) {
                    $this->modified_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
                }
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

        if (parent::beforeSave($insert)) {
            if ($insert || (!$insert && !$this->cat_name)) {
                $cat_name = new SourceMessage();
                $cat_name->location = $location.'_title';
                $cat_name->message = $this->cat_name_i;
                if ($cat_name->save()) {
                    $this->cat_name = $cat_name->id;
                }

            } else {
                $cat_name = SourceMessage::findOne($this->cat_name);
                $cat_name->message = $this->cat_name_i;
                $cat_name->save();
            }

            if ($insert || (!$insert && !$this->cat_desc)) {
                $cat_desc = new SourceMessage();
                $cat_desc->location = $location.'_description';
                $cat_desc->message = $this->cat_desc_i;
                if ($cat_desc->save()) {
                    $this->cat_desc = $cat_desc->id;
                }

            } else {
                $cat_desc = SourceMessage::findOne($this->cat_desc);
                $cat_desc->message = $this->cat_desc_i;
                $cat_desc->save();
            }

            // insert new parent
            if (!isset($this->parent)) {
                $model = new self();
                $model->profile_id = $this->profile_id;
                $model->cat_name_i = $this->parent_id;
                if ($model->save()) {
                    $this->parent_id = $model->cat_id;
                }
            }
        }

        return true;
	}
}
