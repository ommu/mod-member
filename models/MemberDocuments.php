<?php
/**
 * MemberDocuments
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2018 OMMU (www.ommu.id)
 * @created date 1 November 2018, 07:02 WIB
 * @link https://github.com/ommu/mod-member
 *
 * This is the model class for table "ommu_member_documents".
 *
 * The followings are the available columns in table "ommu_member_documents":
 * @property integer $id
 * @property integer $publish
 * @property integer $status
 * @property integer $member_id
 * @property integer $profile_document_id
 * @property string $document_filename
 * @property string $statuses_date
 * @property string $creation_date
 * @property integer $creation_id
 * @property string $modified_date
 * @property integer $modified_id
 * @property string $updated_date
 *
 * The followings are the available model relations:
 * @property Members $member
 * @property MemberProfileDocument $document
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

class MemberDocuments extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;
	use \ommu\traits\FileTrait;

	public $gridForbiddenColumn = ['modified_date', 'modifiedDisplayname', 'updated_date'];
	public $old_document_filename_i;

	public $member_search;
	public $creationDisplayname;
	public $modifiedDisplayname;
	public $profile_search;
	public $document_search;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_member_documents';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['member_id', 'profile_document_id'], 'required'],
			[['publish', 'status', 'member_id', 'profile_document_id', 'creation_id', 'modified_id'], 'integer'],
			[['document_filename'], 'string'],
			[['document_filename', 'statuses_date', 'creation_date', 'modified_date', 'updated_date'], 'safe'],
			[['member_id'], 'exist', 'skipOnError' => true, 'targetClass' => Members::className(), 'targetAttribute' => ['member_id' => 'member_id']],
			[['profile_document_id'], 'exist', 'skipOnError' => true, 'targetClass' => MemberProfileDocument::className(), 'targetAttribute' => ['profile_document_id' => 'id']],
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
			'member_id' => Yii::t('app', 'Member'),
			'profile_document_id' => Yii::t('app', 'Profile Document'),
			'document_filename' => Yii::t('app', 'Document Filename'),
			'statuses_date' => Yii::t('app', 'Statuses Date'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'old_document_filename_i' => Yii::t('app', 'Old Document Filename'),
			'member_search' => Yii::t('app', 'Member'),
			'creationDisplayname' => Yii::t('app', 'Creation'),
			'modifiedDisplayname' => Yii::t('app', 'Modified'),
			'profile_search' => Yii::t('app', 'Profile'),
			'document_search' => Yii::t('app', 'Document'),
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
	public function getProfileDocument()
	{
		return $this->hasOne(MemberProfileDocument::className(), ['id' => 'profile_document_id']);
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
	 * @return \ommu\member\models\query\MemberDocuments the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\member\models\query\MemberDocuments(get_called_class());
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
		$this->templateColumns['member_search'] = [
			'attribute' => 'member_search',
			'value' => function($model, $key, $index, $column) {
				return isset($model->member) ? $model->member->displayname : '-';
			},
			'visible' => !Yii::$app->request->get('member') ? true : false,
		];
		$this->templateColumns['profile_search'] = [
			'attribute' => 'profile_search',
			'value' => function($model, $key, $index, $column) {
				return isset($model->member) ? $model->member->profile->title->message : '-';
			},
			'filter' => MemberProfile::getProfile(),
			'visible' => !Yii::$app->request->get('member') ? true : false,
		];
		$this->templateColumns['document_search'] = [
			'attribute' => 'document_search',
			'value' => function($model, $key, $index, $column) {
				return isset($model->profileDocument) ? $model->profileDocument->document->title->message : '-';
			},
			'filter' => MemberDocumentType::getType(),
			'visible' => !Yii::$app->request->get('document') ? true : false,
		];
		$this->templateColumns['document_filename'] = [
			'attribute' => 'document_filename',
			'value' => function($model, $key, $index, $column) {
				$uploadPath = join('/', [self::getUploadPath(false), $model->id]);
				return $model->document_filename ? Html::img(Url::to(join('/', ['@webpublic', $uploadPath, $model->document_filename])), ['alt' => $model->document_filename]) : '-';
			},
			'format' => 'html',
		];
		$this->templateColumns['statuses_date'] = [
			'attribute' => 'statuses_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->statuses_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'statuses_date'),
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
			'filter' => self::getStatus(),
			'value' => function($model, $key, $index, $column) {
				return self::getStatus($model->status);
			},
			'contentOptions' => ['class' => 'text-center'],
			'format' => 'raw',
		];
		$this->templateColumns['publish'] = [
			'attribute' => 'publish',
			'value' => function($model, $key, $index, $column) {
				$url = Url::to(['publish', 'id' => $model->primaryKey]);
				return $this->quickAction($url, $model->publish);
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
            $model = $model->where(['id' => $id])->one();
            return is_array($column) ? $model : $model->$column;

        } else {
            $model = self::findOne($id);
            return $model;
        }
	}

	/**
	 * function getStatus
	 */
	public static function getStatus($value=null)
	{
		$items = array(
			'0' => Yii::t('app', 'Request'),
			'1' => Yii::t('app', 'Approve'),
			'2' => Yii::t('app', 'Rejected'),
		);

        if ($value !== null) {
            return $items[$value];
        } else {
            return $items;
        }
	}

	/**
	 * after find attributes
	 */
	public function afterFind()
	{
		parent::afterFind();

		$this->old_document_filename_i = $this->document_filename;
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate()
	{
        if (parent::beforeValidate()) {
			$documentFilenameFileType = ['bmp', 'gif', 'jpg', 'png', 'pdf'];
			$document_filename = UploadedFile::getInstance($this, 'document_filename');

            if ($document_filename instanceof UploadedFile && !$document_filename->getHasError()) {
                if (!in_array(strtolower($document_filename->getExtension()), $documentFilenameFileType)) {
					$this->addError('document_filename', Yii::t('app', 'The file {name} cannot be uploaded. Only files with these extensions are allowed: {extensions}', array(
						'{name}' => $document_filename->name,
						'{extensions}' => $this->formatFileType($documentFilenameFileType, false),
					)));
                }
            } else {
                if ($this->isNewRecord || (!$this->isNewRecord && $this->old_document_filename_i == '')) {
                    $this->addError('document_filename', Yii::t('app', '{attribute} cannot be blank.', array('{attribute}' => $this->getAttributeLabel('document_filename'))));
                }
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
				$uploadPath = join('/', [Members::getUploadPath(), $this->member_id]);
				$verwijderenPath = join('/', [Members::getUploadPath(), 'verwijderen']);
				$this->createUploadDirectory(Members::getUploadPath(), $this->member_id);

				$this->document_filename = UploadedFile::getInstance($this, 'document_filename');
                if ($this->document_filename instanceof UploadedFile && !$this->document_filename->getHasError()) {
					$fileName = join('-', [time(), UuidHelper::uuid()]).'.'.strtolower($this->document_filename->getExtension());
                    if ($this->document_filename->saveAs(join('/', [$uploadPath, $fileName]))) {
                        if ($this->old_document_filename_i != '' && file_exists(join('/', [$uploadPath, $this->old_document_filename_i]))) {
                            rename(join('/', [$uploadPath, $this->old_document_filename_i]), join('/', [$verwijderenPath, time().'_change_'.$this->old_document_filename_i]));
                        }
						$this->document_filename = $fileName;
					}
				} else {
                    if ($this->document_filename == '') {
                        $this->document_filename = $this->old_document_filename_i;
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

		$uploadPath = join('/', [Members::getUploadPath(), $this->member_id]);
        $verwijderenPath = join('/', [Members::getUploadPath(), 'verwijderen']);
		$this->createUploadDirectory(Members::getUploadPath(), $this->member_id);

        if ($insert) {
			$this->document_filename = UploadedFile::getInstance($this, 'document_filename');
            if ($this->document_filename instanceof UploadedFile && !$this->document_filename->getHasError()) {
				$fileName = join('-', [time(), UuidHelper::uuid()]).'.'.strtolower($this->document_filename->getExtension());
                if ($this->document_filename->saveAs(join('/', [$uploadPath, $fileName]))) {
                    self::updateAll(['document_filename' => $fileName], ['id' => $this->id]);
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

		$uploadPath = join('/', [Members::getUploadPath(), $this->member_id]);
        $verwijderenPath = join('/', [Members::getUploadPath(), 'verwijderen']);

        if ($this->document_filename != '' && file_exists(join('/', [$uploadPath, $this->document_filename]))) {
            rename(join('/', [$uploadPath, $this->document_filename]), join('/', [$verwijderenPath, time().'_deleted_'.$this->document_filename]));
        }

	}
}
