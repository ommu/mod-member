<?php
/**
 * MemberViewHistory
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2018 OMMU (www.ommu.id)
 * @created date 31 October 2018, 12:49 WIB
 * @link https://github.com/ommu/mod-member
 *
 * This is the model class for table "ommu_member_view_history".
 *
 * The followings are the available columns in table "ommu_member_view_history":
 * @property integer $id
 * @property integer $view_id
 * @property string $view_date
 * @property string $view_ip
 *
 * The followings are the available model relations:
 * @property MemberViews $view
 *
 */

namespace ommu\member\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;

class MemberViewHistory extends \app\components\ActiveRecord
{
	public $gridForbiddenColumn = [];

	public $profile_search;
	public $member_search;
	public $userDisplayname;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_member_view_history';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['view_id'], 'required'],
			[['view_id'], 'integer'],
			[['view_date', 'view_ip'], 'safe'],
			[['view_ip'], 'string', 'max' => 20],
			[['view_id'], 'exist', 'skipOnError' => true, 'targetClass' => MemberViews::className(), 'targetAttribute' => ['view_id' => 'view_id']],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'view_id' => Yii::t('app', 'View'),
			'view_date' => Yii::t('app', 'View Date'),
			'view_ip' => Yii::t('app', 'View IP'),
			'profile_search' => Yii::t('app', 'Profile'),
			'member_search' => Yii::t('app', 'Member'),
			'userDisplayname' => Yii::t('app', 'User'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getView()
	{
		return $this->hasOne(MemberViews::className(), ['view_id' => 'view_id']);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\member\models\query\MemberViewHistory the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\member\models\query\MemberViewHistory(get_called_class());
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
			'contentOptions' => ['class' => 'text-center'],
		];
		$this->templateColumns['profile_search'] = [
			'attribute' => 'profile_search',
			'value' => function($model, $key, $index, $column) {
				return isset($model->view) ? $model->view->member->profile->title->message : '-';
			},
			'filter' => MemberProfile::getProfile(),
			'visible' => !Yii::$app->request->get('view') ? true : false,
		];
		$this->templateColumns['member_search'] = [
			'attribute' => 'member_search',
			'value' => function($model, $key, $index, $column) {
				return isset($model->view) ? $model->view->member->displayname : '-';
			},
			'visible' => !Yii::$app->request->get('view') ? true : false,
		];
		$this->templateColumns['userDisplayname'] = [
			'attribute' => 'userDisplayname',
			'value' => function($model, $key, $index, $column) {
				return isset($model->view) ? $model->view->user->displayname : '-';
			},
			'visible' => !Yii::$app->request->get('view') ? true : false,
		];
		$this->templateColumns['view_date'] = [
			'attribute' => 'view_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->view_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'view_date'),
		];
		$this->templateColumns['view_ip'] = [
			'attribute' => 'view_ip',
			'value' => function($model, $key, $index, $column) {
				return $model->view_ip;
			},
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
            $model = $model->where(['id' => $id])->one();
            return is_array($column) ? $model : $model->$column;

        } else {
            $model = self::findOne($id);
            return $model;
        }
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate()
	{
        if (parent::beforeValidate()) {
			$this->view_ip = $_SERVER['REMOTE_ADDR'];
        }
        return true;
	}
}
