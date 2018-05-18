<?php
/**
 * Members
 *
 * This is the model class for table "ommu_members".
 *
 * The followings are the available columns in table "ommu_members":
 * @property integer $member_id
 * @property integer $publish
 * @property integer $profile_id
 * @property integer $member_private
 * @property string $member_header
 * @property string $member_photo
 * @property string $short_biography
 * @property string $creation_date
 * @property integer $creation_id
 * @property string $modified_date
 * @property integer $modified_id
 * @property string $updated_date
 *
 * @author Agus Susilo <smartgdi@gmail.com>
 * @contact (+62) 857-297-29382
 * @copyright Copyright (c) 2018 ECC UGM (ecc.ft.ugm.ac.id)
 * @created date 20 January 2018, 09:57 WIB
 * @modified date 16 May 2018, 14:13 WIB
 * @modified by Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @link http://ecc.ft.ugm.ac.id
 *
 */

namespace ommu\member\models;

use Yii;
use yii\helpers\Url;
use ommu\users\models\Users;
use app\libraries\grid\GridView;

class Members extends \app\components\ActiveRecord
{
    public $gridForbiddenColumn = [];

	// Variable Search
	public $profile_search;
	public $creation_search;
	public $modified_search;

    /**
     * @return string the associated database table name
     */
    public static function tableName()
    {
        return 'ommu_members';
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
         [['publish', 'profile_id', 'member_private', 'creation_id', 'modified_id'], 'integer'],
            [['profile_id', 'member_header', 'member_photo', 'short_biography', 'creation_id', 'modified_id'], 'required'],
            [['member_header', 'member_photo', 'short_biography'], 'string'],
            [['creation_date', 'modified_date', 'updated_date'], 'safe'],
            [['profile_id'], 'exist', 'skipOnError' => true, 'targetClass' => MemberProfile::className(), 'targetAttribute' => ['profile_id' => 'profile_id']],
      ];
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
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
			'member_id' => Yii::t('app', 'Member'),
			'publish' => Yii::t('app', 'Publish'),
			'profile_id' => Yii::t('app', 'Profile'),
			'member_private' => Yii::t('app', 'Member Private'),
			'member_header' => Yii::t('app', 'Member Header'),
			'member_photo' => Yii::t('app', 'Member Photo'),
			'short_biography' => Yii::t('app', 'Short Biography'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'profile_search' => Yii::t('app', 'Profile'),
			'creation_search' => Yii::t('app', 'Creation'),
			'modified_search' => Yii::t('app', 'Modified'),
        ];
    }

    /**
     * @inheritdoc
     * @return MembersQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new MembersQuery(get_called_class());
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
        if(!isset($_GET['profile'])) {
            $this->templateColumns['profile_search'] = [
                'attribute' => 'profile_search',
                'value' => function($model, $key, $index, $column) {
                    return $model->profile->profile_name;
                },
            ];
        }
        $this->templateColumns['member_header'] = 'member_header';
        $this->templateColumns['member_photo'] = 'member_photo';
        $this->templateColumns['short_biography'] = 'short_biography';
        $this->templateColumns['creation_date'] = [
            'attribute' => 'creation_date',
            'filter'    => \yii\jui\DatePicker::widget([
                'dateFormat' => 'yyyy-MM-dd',
                'attribute' => 'creation_date',
                'model'  => $this,
            ]),
            'value' => function($model, $key, $index, $column) {
                if(!in_array($model->creation_date, 
					['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00'])) {
                    return Yii::$app->formatter->format($model->creation_date, 'date'/*datetime*/);
                }else {
                    return '-';
                }
            },
            'format'    => 'html',
        ];
        if(!Yii::$app->request->get('creation')) {
            $this->templateColumns['creation_search'] = [
                'attribute' => 'creation_search',
                'value' => function($model, $key, $index, $column) {
                    return isset($model->creation->displayname) ? $model->creation->displayname : '-';
                },
            ];
        }
        $this->templateColumns['modified_date'] = [
            'attribute' => 'modified_date',
            'filter'    => \yii\jui\DatePicker::widget([
                'dateFormat' => 'yyyy-MM-dd',
                'attribute' => 'modified_date',
                'model'  => $this,
            ]),
            'value' => function($model, $key, $index, $column) {
                if(!in_array($model->modified_date, 
					['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00'])) {
                    return Yii::$app->formatter->format($model->modified_date, 'date'/*datetime*/);
                }else {
                    return '-';
                }
            },
            'format'    => 'html',
        ];
        if(!Yii::$app->request->get('modified')) {
            $this->templateColumns['modified_search'] = [
                'attribute' => 'modified_search',
                'value' => function($model, $key, $index, $column) {
                    return isset($model->modified->displayname) ? $model->modified->displayname : '-';
                },
            ];
        }
        $this->templateColumns['updated_date'] = [
            'attribute' => 'updated_date',
            'filter'    => \yii\jui\DatePicker::widget([
                'dateFormat' => 'yyyy-MM-dd',
                'attribute' => 'updated_date',
                'model'  => $this,
            ]),
            'value' => function($model, $key, $index, $column) {
                if(!in_array($model->updated_date, 
					['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00'])) {
                    return Yii::$app->formatter->format($model->updated_date, 'date'/*datetime*/);
                }else {
                    return '-';
                }
            },
            'format'    => 'html',
        ];
        $this->templateColumns['member_private'] = [
            'attribute' => 'member_private',
            'value' => function($model, $key, $index, $column) {
                return $model->member_private;
            },
            'contentOptions' => ['class'=>'center'],
        ];
        if(!Yii::$app->request->get('trash')) {
            $this->templateColumns['publish'] = [
                'attribute' => 'publish',
                'filter' => GridView::getFilterYesNo(),
                'value' => function($model, $key, $index, $column) {
                    $url = Url::to(['publish', 'id'=>$model->primaryKey]);
                    return GridView::getPublish($url, $model->publish);
                },
                'contentOptions' => ['class'=>'center'],
                'format'    => 'raw',
            ];
        }
    }

    /**
     * before validate attributes
     */
    public function beforeValidate() 
    {
        if(parent::beforeValidate()) {
			if($this->isNewRecord) {
				$this->creation_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : '0';
				$this->modified_id = 0;
			}else
				$this->modified_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : '0';
        }
        return true;
    }

}
