<?php
/**
 * Members
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2018 OMMU (www.ommu.id)
 * @created date 30 October 2018, 15:22 WIB
 * @created date 2 November 2018, 06:46 WIB
 * @link https://github.com/ommu/mod-member
 *
 * This is the model class for table "ommu_members".
 *
 * The followings are the available columns in table "ommu_members":
 * @property integer $member_id
 * @property integer $publish
 * @property integer $approved
 * @property integer $profile_id
 * @property integer $member_private
 * @property string $username
 * @property string $displayname
 * @property string $photo_header
 * @property string $photo_profile
 * @property string $short_biography
 * @property string $approved_date
 * @property integer $approved_id
 * @property string $lastlogin_date
 * @property integer $lastlogin_user_id
 * @property string $lastlogin_ip
 * @property string $lastlogin_from
 * @property string $creation_date
 * @property integer $creation_id
 * @property string $modified_date
 * @property integer $modified_id
 * @property string $updated_date
 *
 * The followings are the available model relations:
 * @property IpediaCompanies[] $companies
 * @property MemberCompany[] $companies
 * @property MemberDocuments[] $documents
 * @property MemberFollowers[] $followers
 * @property MemberHistoryDisplayname[] $displaynames
 * @property MemberHistoryUsername[] $usernames
 * @property MemberRecruiter[] $recruiters
 * @property MemberRecruiter[] $recruiters0
 * @property MemberUser[] $users
 * @property MemberViews[] $views
 * @property MemberProfile $profile
 * @property Users $approvedRltn
 * @property Users $creation
 * @property Users $modified
 *
 */

namespace ommu\member\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\UploadedFile;
use thamtech\uuid\helpers\UuidHelper;
use app\models\Users;
use ommu\member\models\view\MemberUser as MemberUserView;

class Members extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;
	use \ommu\traits\FileTrait;

	public $gridForbiddenColumn = ['photo_header', 'short_biography', 'approved_date', 'approved_search', 'lastloginUserDisplayname', 'lastlogin_ip', 'lastlogin_from', 'creation_date', 'creationDisplayname', 'modified_date', 'modifiedDisplayname', 'updated_date'];
	public $old_photo_header_i;
	public $old_photo_profile_i;
	public $old_approved_i;
    // owner user_id
    public $user_id;

	public $approved_search;
	public $lastloginUserDisplayname;
	public $creationDisplayname;
	public $modifiedDisplayname;

	const SCENARIO_MEMBER_COMPANY = 'company';

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_members';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['profile_id', 'member_private', 'displayname'], 'required'],
			[['publish', 'approved', 'profile_id', 'member_private', 'approved_id', 'lastlogin_user_id', 'creation_id', 'modified_id'], 'integer'],
			[['photo_header', 'photo_profile', 'short_biography'], 'string'],
			[['username', 'photo_header', 'photo_profile', 'short_biography', 'approved_date', 'creation_date', 'modified_date', 'updated_date'], 'safe'],
			['username', 'unique'],
			[['username', 'lastlogin_ip', 'lastlogin_from'], 'string', 'max' => 32],
			[['displayname'], 'string', 'max' => 64],
			[['profile_id'], 'exist', 'skipOnError' => true, 'targetClass' => MemberProfile::className(), 'targetAttribute' => ['profile_id' => 'profile_id']],
		];
	}

	public function scenarios()
	{
		$scenarios = parent::scenarios();
		$scenarios[self::SCENARIO_MEMBER_COMPANY] = ['publish', 'approved', 'member_private', 'username', 'displayname', 'photo_header', 'photo_profile'];
		return $scenarios;
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'member_id' => Yii::t('app', 'Member'),
			'publish' => Yii::t('app', 'Publish'),
			'approved' => Yii::t('app', 'Approved'),
			'profile_id' => Yii::t('app', 'Profile'),
			'member_private' => Yii::t('app', 'Member'),
			'username' => Yii::t('app', 'Username'),
			'displayname' => Yii::t('app', 'Displayname'),
			'photo_header' => Yii::t('app', 'Photo Header'),
			'photo_profile' => Yii::t('app', 'Photo Profile'),
			'short_biography' => Yii::t('app', 'Short Biography'),
			'approved_date' => Yii::t('app', 'Approved Date'),
			'approved_id' => Yii::t('app', 'Approved'),
			'lastlogin_date' => Yii::t('app', 'Lastlogin Date'),
			'lastlogin_user_id' => Yii::t('app', 'Lastlogin User'),
			'lastlogin_ip' => Yii::t('app', 'Lastlogin IP'),
			'lastlogin_from' => Yii::t('app', 'Lastlogin From'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'old_photo_header_i' => Yii::t('app', 'Old Photo Header'),
			'old_photo_profile_i' => Yii::t('app', 'Old Photo Profile'),
			'approved_search' => Yii::t('app', 'Approved'),
			'creationDisplayname' => Yii::t('app', 'Creation'),
			'modifiedDisplayname' => Yii::t('app', 'Modified'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	// public function getCompanies()
	// {
	// 	return $this->hasMany(\app\modules\ipedia\models\IpediaCompanies::className(), ['member_id' => 'member_id'])
	//		->alias('companies')
	//		->andOnCondition([sprintf('%s.publish', 'companies') => 1]);
	// }

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCompanies()
	{
		return $this->hasMany(MemberCompany::className(), ['member_id' => 'member_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getDocuments()
	{
		return $this->hasMany(MemberDocuments::className(), ['member_id' => 'member_id'])
			->alias('documents')
			->andOnCondition([sprintf('%s.publish', 'documents') => 1]);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getFollowers()
	{
		return $this->hasMany(MemberFollowers::className(), ['member_id' => 'member_id'])
			->alias('followers')
			->andOnCondition([sprintf('%s.publish', 'followers') => 1]);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getDisplaynames()
	{
		return $this->hasMany(MemberHistoryDisplayname::className(), ['member_id' => 'member_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getUsernames()
	{
		return $this->hasMany(MemberHistoryUsername::className(), ['member_id' => 'member_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getRecruiters()
	{
		return $this->hasMany(MemberRecruiter::className(), ['member_id' => 'member_id'])
			->alias('recruiters')
			->andOnCondition([sprintf('%s.publish', 'recruiters') => 1]);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getRecruiters0()
	{
		return $this->hasMany(MemberRecruiter::className(), ['recruiter_id' => 'member_id'])
			->alias('recruiters0')
			->andOnCondition([sprintf('%s.publish', 'recruiters0') => 1]);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getUsers()
	{
		return $this->hasMany(MemberUser::className(), ['member_id' => 'member_id'])
			->alias('users')
			->andOnCondition([sprintf('%s.publish', 'users') => 1]);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getViews()
	{
		return $this->hasMany(MemberViews::className(), ['member_id' => 'member_id'])
			->alias('views')
			->andOnCondition([sprintf('%s.publish', 'views') => 1]);
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
	public function getApprovedRltn()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'approved_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getLastloginUser()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'lastlogin_user_id']);
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
     * @return \yii\db\ActiveQuery
     */
    public function getUserOwner()
    {
        return $this->hasOne(MemberUser::className(), ['member_id' => 'member_id'])
            ->alias('userOwner')
            ->andOnCondition([sprintf('%s.publish', 'userOwner') => 1, sprintf('%s.owner', 'userOwner') => 1]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserInfo()
    {
        return $this->hasOne(Users::className(), ['user_id' => 'user_id'])
            ->via('userOwner');
    }

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getUser()
	{
		return $this->hasOne(MemberUserView::className(), ['member_id' => 'member_id']);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\member\models\query\Members the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\member\models\query\Members(get_called_class());
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
		$this->templateColumns['photo_profile'] = [
			'attribute' => 'photo_profile',
			'value' => function($model, $key, $index, $column) {
				$uploadPath = join('/', [self::getUploadPath(false), $model->member_id]);
				return $model->photo_profile ? Html::img(Url::to(join('/', ['@webpublic', $uploadPath, $model->photo_profile])), ['alt' => $model->photo_profile]) : '-';
			},
			'format' => 'html',
		];
		$this->templateColumns['profile_id'] = [
			'attribute' => 'profile_id',
			'value' => function($model, $key, $index, $column) {
				return isset($model->profile) ? $model->profile->title->message : '-';
			},
			'filter' => MemberProfile::getProfile(),
			'visible' => !Yii::$app->request->get('profile') ? true : false,
		];
		$this->templateColumns['username'] = [
			'attribute' => 'username',
			'value' => function($model, $key, $index, $column) {
				return $model->username;
			},
		];
		$this->templateColumns['displayname'] = [
			'attribute' => 'displayname',
			'value' => function($model, $key, $index, $column) {
				return $model->displayname;
			},
		];
		$this->templateColumns['photo_header'] = [
			'attribute' => 'photo_header',
			'value' => function($model, $key, $index, $column) {
				$uploadPath = join('/', [self::getUploadPath(false), $model->member_id]);
				return $model->photo_header ? Html::img(Url::to(join('/', ['@webpublic', $uploadPath, $model->photo_header])), ['alt' => $model->photo_header]) : '-';
			},
			'format' => 'html',
		];
		$this->templateColumns['short_biography'] = [
			'attribute' => 'short_biography',
			'value' => function($model, $key, $index, $column) {
				return $model->short_biography;
			},
		];
		$this->templateColumns['approved_date'] = [
			'attribute' => 'approved_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->approved_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'approved_date'),
		];
		$this->templateColumns['approved_search'] = [
			'attribute' => 'approved_search',
			'value' => function($model, $key, $index, $column) {
				return isset($model->approvedRltn) ? $model->approvedRltn->displayname : '-';
			},
			'visible' => !Yii::$app->request->get('approved') ? true : false,
		];
		$this->templateColumns['creation_date'] = [
			'attribute' => 'creation_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->creation_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'creation_date'),
		];
		$this->templateColumns['lastlogin_date'] = [
			'attribute' => 'lastlogin_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->lastlogin_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'lastlogin_date'),
		];
        $this->templateColumns['lastloginUserDisplayname'] = [
            'attribute' => 'lastloginUserDisplayname',
            'value' => function($model, $key, $index, $column) {
                return isset($model->lastloginUser) ? $model->lastloginUser->displayname : '-';
                // return $model->lastloginUserDisplayname;
            },
            'visible' => !Yii::$app->request->get('lastloginUser') ? true : false,
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
		$this->templateColumns['approved'] = [
			'attribute' => 'approved',
			'value' => function($model, $key, $index, $column) {
				return $this->filterYesNo($model->approved);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class' => 'text-center'],
		];
		$this->templateColumns['member_private'] = [
			'attribute' => 'member_private',
			'filter' => self::getMemberPrivate(),
			'value' => function($model, $key, $index, $column) {
				return self::getMemberPrivate($model->member_private);
			},
			'contentOptions' => ['class' => 'text-center'],
			'format' => 'raw',
		];
		$this->templateColumns['publish'] = [
			'attribute' => 'publish',
			'value' => function($model, $key, $index, $column) {
				$url = Url::to(['publish', 'id' => $model->primaryKey]);
				return $this->quickAction($url, $model->publish, 'Enable,Disable');
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class' => 'text-center'],
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
            $model = $model->where(['member_id' => $id])->one();
            return is_array($column) ? $model : $model->$column;

        } else {
            $model = self::findOne($id);
            return $model;
        }
	}

	/**
	 * function getMemberPrivate
	 */
	public static function getMemberPrivate($value=null)
	{
		$items = array(
			'0' => Yii::t('app', 'Public'),
			'1' => Yii::t('app', 'Private'),
		);

        if ($value !== null) {
            return $items[$value];
        } else {
            return $items;
        }
	}

	/**
	 * @param returnAlias set true jika ingin kembaliannya path alias atau false jika ingin string
	 * relative path. default true.
	 */
	public static function getUploadPath($returnAlias=true)
	{
		return ($returnAlias ? Yii::getAlias('@public/member') : 'member');
	}

    /**
     * {@inheritdoc}
     */
    protected function getProfileSetting()
    {
        $model = MemberSetting::getInfo(['personal_profile_id', 'company_profile_id', 'group_profile_id']);

        return $model;
    }

    /**
     * {@inheritdoc}
     */
    public function getProfilePersonal()
    {
        $model = $this->profileSetting;

        if ($model == null) {
            return null;
        }

        return $model->personal_profile_id;
    }

    /**
     * {@inheritdoc}
     */
    public function getProfileCompany()
    {
        $model = $this->profileSetting;

        if ($model == null) {
            return null;
        }

        return $model->company_profile_id;
    }

    /**
     * {@inheritdoc}
     */
    public function getProfileGroup()
    {
        $model = $this->profileSetting;

        if ($model == null) {
            return null;
        }

        return $model->group_profile_id;
    }

    /**
     * {@inheritdoc}
     */
    public static function getMemberUserId($username)
    {
        $model = self::find()
            ->select(['member_id', 'profile_id'])
            ->where(['publish' => 1])
            ->andWhere(['username' => $username]);
        if (isset(Yii::$app->params['newsfeed']['profileIgnore'])) {
            $model->andWhere(['NOT IN', 'profile_id', Yii::$app->params['newsfeed']['profileIgnore']]);
        }
        $model = $model->one();

        $memberId = '';
        if ($model != null) {
            $memberId = $model->member_id;
        }

        $userId = '';
        if ($model != null && $model->profile_id == $model->profilePersonal) {
            if (isset($model->userOwner)) {
                $userId = $model->userOwner->user_id;
            }
        }

        return [
            'member_id' => $memberId,
            'user_id' => $userId,
        ];
    }

	/**
	 * after find attributes
	 */
	public function afterFind()
	{
		parent::afterFind();

		$this->old_photo_header_i = $this->photo_header;
		$this->old_photo_profile_i = $this->photo_profile;
		$this->old_approved_i = $this->approved;
        $this->user_id = $this->userOwner->user_id;
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate()
	{
		$action = strtolower(Yii::$app->controller->action->id);

        if (parent::beforeValidate()) {
			$photoHeaderFileType = ['bmp', 'gif', 'jpg', 'png'];
			// $this->photo_header = UploadedFile::getInstance($this, 'photo_header');
            if ($this->photo_header instanceof UploadedFile && !$this->photo_header->getHasError()) {
                if (!in_array(strtolower($this->photo_header->getExtension()), $photoHeaderFileType)) {
					$this->addError('photo_header', Yii::t('app', 'The file {name} cannot be uploaded. Only files with these extensions are allowed: {extensions}', array(
						'{name}' => $this->photo_header->name,
						'{extensions}' => $this->formatFileType($photoHeaderFileType, false),
					)));
				}
			} /* else {
                if ($this->isNewRecord || (!$this->isNewRecord && $this->old_photo_header_i == '')) {
                    $this->addError('photo_header', Yii::t('app', '{attribute} cannot be blank.', array('{attribute}' => $this->getAttributeLabel('photo_header'))));
                }
			} */

			$photoProfileFileType = ['bmp', 'gif', 'jpg', 'png'];
			// $this->photo_profile = UploadedFile::getInstance($this, 'photo_profile');
            if ($this->photo_profile instanceof UploadedFile && !$this->photo_profile->getHasError()) {
                if (!in_array(strtolower($this->photo_profile->getExtension()), $photoProfileFileType)) {
					$this->addError('photo_profile', Yii::t('app', 'The file {name} cannot be uploaded. Only files with these extensions are allowed: {extensions}', array(
						'{name}' => $this->photo_profile->name,
						'{extensions}' => $this->formatFileType($photoProfileFileType, false),
					)));
				}
			} /* else {
                if ($this->isNewRecord || (!$this->isNewRecord && $this->old_photo_profile_i == '')) {
                    $this->addError('photo_profile', Yii::t('app', '{attribute} cannot be blank.', array('{attribute}' => $this->getAttributeLabel('photo_profile'))));
                }
			} */

            if ($action == 'approved' && $this->old_approved_i != $this->approved) {
                $this->approved_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
            }

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
        if (parent::beforeSave($insert)) {
            if (!$insert) {
                $uploadPath = join('/', [self::getUploadPath(), $this->member_id]);
                $verwijderenPath = join('/', [self::getUploadPath(), 'verwijderen']);
                $this->createUploadDirectory(self::getUploadPath(), $this->member_id);

				// $this->photo_header = UploadedFile::getInstance($this, 'photo_header');
                if ($this->photo_header instanceof UploadedFile && !$this->photo_header->getHasError()) {
					$fileName = join('-', [time(), UuidHelper::uuid()]).'.'.strtolower($this->photo_header->getExtension());
                    if ($this->photo_header->saveAs(join('/', [$uploadPath, $fileName]))) {
                        if ($this->old_photo_header_i != '' && file_exists(join('/', [$uploadPath, $this->old_photo_header_i]))) {
                            rename(join('/', [$uploadPath, $this->old_photo_header_i]), join('/', [$verwijderenPath, time().'_change_'.$this->old_photo_header_i]));
                        }
						$this->photo_header = $fileName;
					}
				} else {
                    if ($this->photo_header == '') {
                        $this->photo_header = $this->old_photo_header_i;
                    }
				}

				// $this->photo_profile = UploadedFile::getInstance($this, 'photo_profile');
                if ($this->photo_profile instanceof UploadedFile && !$this->photo_profile->getHasError()) {
					$fileName = join('-', [time(), UuidHelper::uuid()]).'.'.strtolower($this->photo_profile->getExtension());
                    if ($this->photo_profile->saveAs(join('/', [$uploadPath, $fileName]))) {
                        if ($this->old_photo_profile_i != '' && file_exists(join('/', [$uploadPath, $this->old_photo_profile_i]))) {
                            rename(join('/', [$uploadPath, $this->old_photo_profile_i]), join('/', [$verwijderenPath, time().'_change_'.$this->old_photo_profile_i]));
                        }
						$this->photo_profile = $fileName;
					}
				} else {
                    if ($this->photo_profile == '') {
                        $this->photo_profile = $this->old_photo_profile_i;
                    }
				}

            }
        }
        return true;
	}

	/**
	 * After save attributes
	 */
	public function afterSave($insert, $changedAttributes)
	{
        parent::afterSave($insert, $changedAttributes);

        $uploadPath = join('/', [self::getUploadPath(), $this->member_id]);
        $verwijderenPath = join('/', [self::getUploadPath(), 'verwijderen']);
        $this->createUploadDirectory(self::getUploadPath(), $this->member_id);

        if ($insert) {
			// $this->photo_header = UploadedFile::getInstance($this, 'photo_header');
            if ($this->photo_header instanceof UploadedFile && !$this->photo_header->getHasError()) {
				$fileName = join('-', [time(), UuidHelper::uuid()]).'.'.strtolower($this->photo_header->getExtension());
                if ($this->photo_header->saveAs(join('/', [$uploadPath, $fileName]))) {
                    self::updateAll(['photo_header' => $fileName], ['member_id' => $this->member_id]);
                }
			}

			// $this->photo_profile = UploadedFile::getInstance($this, 'photo_profile');
            if ($this->photo_profile instanceof UploadedFile && !$this->photo_profile->getHasError()) {
				$fileName = join('-', [time(), UuidHelper::uuid()]).'.'.strtolower($this->photo_profile->getExtension());
                if ($this->photo_profile->saveAs(join('/', [$uploadPath, $fileName]))) {
                    self::updateAll(['photo_profile' => $fileName], ['member_id' => $this->member_id]);
                }
			}

		} else {
            if (array_key_exists('publish', $changedAttributes) && $this->publish != $changedAttributes['publish'] && $this->publish == 2) {
                if (class_exists('ommu\ipedia\models\IpediaCompanies')) {
					\ommu\ipedia\models\IpediaCompanies::find()->where(['member_id' => $this->member_id])->updateAttributes(['member_id' => null]);
				}
			}
		}
	}

	/**
	 * After delete attributes
	 */
	public function afterDelete()
	{
        parent::afterDelete();

        $uploadPath = join('/', [self::getUploadPath(), $this->member_id]);
        $verwijderenPath = join('/', [self::getUploadPath(), 'verwijderen']);

        if ($this->photo_header != '' && file_exists(join('/', [$uploadPath, $this->photo_header]))) {
            rename(join('/', [$uploadPath, $this->photo_header]), join('/', [$verwijderenPath, time().'_deleted_'.$this->photo_header]));
        }

        if ($this->photo_profile != '' && file_exists(join('/', [$uploadPath, $this->photo_profile]))) {
            rename(join('/', [$uploadPath, $this->photo_profile]), join('/', [$verwijderenPath, time().'_deleted_'.$this->photo_profile]));
        }

	}
}
