<?php
/**
 * MemberFriends
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2018 OMMU (www.ommu.id)
 * @created date 31 October 2018, 05:27 WIB
 * @link https://github.com/ommu/mod-member
 *
 * This is the model class for table "ommu_member_friends".
 *
 * The followings are the available columns in table "ommu_member_friends":
 * @property integer $id
 * @property integer $type_id
 * @property integer $user_id
 * @property integer $request_id
 * @property string $request_date
 * @property string $modified_date
 * @property integer $modified_id
 *
 * The followings are the available model relations:
 * @property MemberFriendHistory[] $histories
 * @property MemberFriendType $type
 * @property Users $user
 * @property Users $request
 * @property Users $modified
 *
 */

namespace ommu\member\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Users;

class MemberFriends extends \app\components\ActiveRecord
{
	public $gridForbiddenColumn = ['modified_date', 'modifiedDisplayname'];

	public $userDisplayname;
	public $request_search;
	public $modifiedDisplayname;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_member_friends';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['type_id', 'user_id', 'request_id'], 'required'],
			[['type_id', 'user_id', 'request_id', 'modified_id'], 'integer'],
			[['request_date', 'modified_date'], 'safe'],
			[['type_id'], 'exist', 'skipOnError' => true, 'targetClass' => MemberFriendType::className(), 'targetAttribute' => ['type_id' => 'id']],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'type_id' => Yii::t('app', 'Type'),
			'user_id' => Yii::t('app', 'User'),
			'request_id' => Yii::t('app', 'Request'),
			'request_date' => Yii::t('app', 'Request Date'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'userDisplayname' => Yii::t('app', 'User'),
			'request_search' => Yii::t('app', 'Request'),
			'modifiedDisplayname' => Yii::t('app', 'Modified'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getHistories()
	{
		return $this->hasMany(MemberFriendHistory::className(), ['friend_id' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getType()
	{
		return $this->hasOne(MemberFriendType::className(), ['id' => 'type_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getUser()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'user_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getRequest()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'request_id']);
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
	 * @return \ommu\member\models\query\MemberFriends the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\member\models\query\MemberFriends(get_called_class());
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
		$this->templateColumns['type_id'] = [
			'attribute' => 'type_id',
			'value' => function($model, $key, $index, $column) {
				return isset($model->type) ? $model->type->title->message : '-';
			},
			'filter' => MemberFriendType::getType(),
			'visible' => !Yii::$app->request->get('type') ? true : false,
		];
		$this->templateColumns['userDisplayname'] = [
			'attribute' => 'userDisplayname',
			'value' => function($model, $key, $index, $column) {
				return isset($model->user) ? $model->user->displayname : '-';
			},
			'visible' => !Yii::$app->request->get('user') ? true : false,
		];
		$this->templateColumns['request_search'] = [
			'attribute' => 'request_search',
			'value' => function($model, $key, $index, $column) {
				return isset($model->request) ? $model->request->displayname : '-';
			},
			'visible' => !Yii::$app->request->get('request') ? true : false,
		];
		$this->templateColumns['request_date'] = [
			'attribute' => 'request_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->request_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'request_date'),
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
            if (!$this->isNewRecord) {
                if ($this->modified_id == null) {
                    $this->modified_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
                }
            }
        }
        return true;
	}
}
