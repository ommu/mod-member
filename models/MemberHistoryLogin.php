<?php
/**
 * MemberHistoryLogin
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2020 Upgrad.id
 * @created date 12 October 2020, 09:12 WIB
 * @link https://github.com/ommu/mod-member
 *
 * This is the model class for table "ommu_member_history_login".
 *
 * The followings are the available columns in table "ommu_member_history_login":
 * @property integer $id
 * @property integer $member_id
 * @property integer $user_id
 * @property string $lastlogin_date
 * @property string $lastlogin_ip
 * @property string $lastlogin_from
 *
 * The followings are the available model relations:
 * @property Members $member
 * @property Users $user
 *
 */

namespace ommu\member\models;

use Yii;
use app\models\Users;
use ommu\member\models\Members;

class MemberHistoryLogin extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;

	public $gridForbiddenColumn = [];

	public $memberDisplayname;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_member_history_login';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['member_id', 'user_id', 'lastlogin_ip', 'lastlogin_from'], 'required'],
			[['member_id', 'user_id'], 'integer'],
			[['lastlogin_date'], 'safe'],
			[['lastlogin_ip', 'lastlogin_from'], 'string', 'max' => 32],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'member_id' => Yii::t('app', 'Member'),
			'user_id' => Yii::t('app', 'User'),
			'lastlogin_date' => Yii::t('app', 'Lastlogin Date'),
			'lastlogin_ip' => Yii::t('app', 'Lastlogin Ip'),
			'lastlogin_from' => Yii::t('app', 'Lastlogin From'),
			'memberDisplayname' => Yii::t('app', 'Member'),
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
	public function getUser()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'user_id']);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\member\models\query\MemberHistoryLogin the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\member\models\query\MemberHistoryLogin(get_called_class());
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
		$this->templateColumns['memberDisplayname'] = [
			'attribute' => 'memberDisplayname',
			'value' => function($model, $key, $index, $column) {
                $memberDisplayname = isset($model->member) ? $model->member->displayname : '-';
                $userDisplayname = isset($model->user) ? $model->user->displayname : '-';
                if ($userDisplayname != '-' && $memberDisplayname != $userDisplayname) {
                    return $memberDisplayname.'<br/>'.$userDisplayname;
                }
                return $memberDisplayname;
				// return $model->memberDisplayname;
			},
            'format' => 'html',
			'visible' => !Yii::$app->request->get('member') ? true : false,
		];
		$this->templateColumns['lastlogin_date'] = [
			'attribute' => 'lastlogin_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->lastlogin_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'lastlogin_date'),
		];
		$this->templateColumns['lastlogin_ip'] = [
			'attribute' => 'lastlogin_ip',
			'value' => function($model, $key, $index, $column) {
				return $model->lastlogin_ip;
			},
		];
		$this->templateColumns['lastlogin_from'] = [
			'attribute' => 'lastlogin_from',
			'value' => function($model, $key, $index, $column) {
				return $model->lastlogin_from;
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
	 * after find attributes
	 */
	public function afterFind()
	{
		parent::afterFind();

		// $this->memberDisplayname = isset($this->member) ? $this->member->displayname : '-';
	}
}
