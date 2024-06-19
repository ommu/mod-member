<?php
/**
 * MemberFriendHistory
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2018 OMMU (www.ommu.id)
 * @created date 31 October 2018, 05:20 WIB
 * @link https://github.com/ommu/mod-member
 *
 * This is the model class for table "ommu_member_friend_history".
 *
 * The followings are the available columns in table "ommu_member_friend_history":
 * @property integer $id
 * @property integer $type_id
 * @property integer $friend_id
 * @property string $creation_date
 * @property integer $creation_id
 *
 * The followings are the available model relations:
 * @property MemberFriends $friend
 * @property MemberFriendType $type
 * @property Users $creation
 *
 */

namespace ommu\member\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Users;

class MemberFriendHistory extends \app\components\ActiveRecord
{
	public $gridForbiddenColumn = [];

	public $creationDisplayname;
	public $st_user_search;
	public $nd_user_search;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_member_friend_history';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['type_id', 'friend_id'], 'required'],
			[['type_id', 'friend_id', 'creation_id'], 'integer'],
			[['creation_date'], 'safe'],
			[['friend_id'], 'exist', 'skipOnError' => true, 'targetClass' => MemberFriends::className(), 'targetAttribute' => ['friend_id' => 'id']],
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
			'friend_id' => Yii::t('app', 'Friend'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'creationDisplayname' => Yii::t('app', 'Creation'),
			'st_user_search' => Yii::t('app', '1st User'),
			'nd_user_search' => Yii::t('app', '2nd User'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getFriend()
	{
		return $this->hasOne(MemberFriends::className(), ['id' => 'friend_id']);
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
	public function getCreation()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'creation_id']);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\member\models\query\MemberFriendHistory the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\member\models\query\MemberFriendHistory(get_called_class());
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
		$this->templateColumns['st_user_search'] = [
			'attribute' => 'st_user_search',
			'value' => function($model, $key, $index, $column) {
				return isset($model->friend) ? $model->friend->user->displayname : '-';
			},
			'visible' => !Yii::$app->request->get('friend') ? true : false,
		];
		$this->templateColumns['nd_user_search'] = [
			'attribute' => 'nd_user_search',
			'value' => function($model, $key, $index, $column) {
				return isset($model->friend) ? $model->friend->request->displayname : '-';
			},
			'visible' => !Yii::$app->request->get('friend') ? true : false,
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
            if ($this->isNewRecord) {
                if ($this->creation_id == null) {
                    $this->creation_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
                }
            }
        }
        return true;
	}
}
