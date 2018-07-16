<?php
/**
 * Members
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @created date 2 March 2017, 09:36 WIB
 * @link https://github.com/ommu/mod-member
 *
 * This is the template for generating the model class of a specified table.
 * - $this: the ModelCode object
 * - $tableName: the table name for this class (prefix is already removed if necessary)
 * - $modelClass: the model class name
 * - $columns: list of table columns (name=>CDbColumnSchema)
 * - $labels: list of attribute labels (name=>label)
 * - $rules: list of validation rules
 * - $relations: list of relations (name=>relation declaration)
 *
 * --------------------------------------------------------------------------------------
 *
 * This is the model class for table "ommu_members".
 *
 * The followings are the available columns in table 'ommu_members':
 * @property string $member_id
 * @property integer $publish
 * @property integer $profile_id
 * @property integer $member_private
 * @property string $member_header
 * @property string $member_photo
 * @property string $short_biography
 * @property string $creation_date
 * @property string $creation_id
 * @property string $modified_date
 * @property string $modified_id
 *
 * The followings are the available model relations:
 * @property CvBio[] $CvBios
 * @property CvEducationAssure[] $CvEducationAssures
 * @property CvEducations[] $CvEducations
 * @property CvExperienceAssure[] $CvExperienceAssures
 * @property CvExperiences[] $CvExperiences
 * @property CvOrganizations[] $CvOrganizations
 * @property CvPortfolioAssure[] $CvPortfolioAssures
 * @property CvPortfolios[] $CvPortfolioses
 * @property CvPositiveNegative[] $CvPositiveNegatives
 * @property CvReferences[] $CvReferences
 * @property CvReferences[] $CvReferences1
 * @property CvSkillAssure[] $CvSkillAssures
 * @property CvSkills[] $CvSkills
 * @property CvTrainings[] $CvTrainings
 * @property MemberCompany[] $MemberCompanies
 * @property MemberUser[] $MemberUsers
 * @property MemberProfile $profile
 * @property Vacancies[] $Vacancies
 */
class Members extends CActiveRecord
{
	use UtilityTrait;
	use GridViewTrait;

	public $defaultColumns = array();
	public $old_member_header_i;
	public $old_member_photo_i;
	public $member_user_i;
	
	// Variable Search
	public $member_search;
	public $creation_search;
	public $modified_search;
	public $user_search;
	public $like_search;
	public $view_search;

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Members the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ommu_members';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('profile_id', 'required'),
			array('publish, profile_id, member_private', 'numerical', 'integerOnly'=>true),
			array('creation_id, modified_id', 'length', 'max'=>11),
			array('short_biography', 'length', 'max'=>160),
			array('member_header, member_photo, short_biography,
				old_member_header_i, old_member_photo_i, member_user_i', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('member_id, publish, profile_id, member_private, member_header, member_photo, short_biography, creation_date, creation_id, modified_date, modified_id, 
				member_search creation_search, modified_search, user_search, like_search, view_search', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'view' => array(self::BELONGS_TO, 'ViewMembers', 'member_id'),
			'profile' => array(self::BELONGS_TO, 'MemberProfile', 'profile_id'),
			'creation' => array(self::BELONGS_TO, 'Users', 'creation_id'),
			'modified' => array(self::BELONGS_TO, 'Users', 'modified_id'),
			'users' => array(self::HAS_MANY, 'MemberUser', 'member_id'),
			'ommuCvBios_relation' => array(self::HAS_MANY, 'OmmuCvBio', 'member_id'),
			'ommuCvEducationAssures_relation' => array(self::HAS_MANY, 'OmmuCvEducationAssure', 'member_id'),
			'ommuCvEducations_relation' => array(self::HAS_MANY, 'OmmuCvEducations', 'member_id'),
			'ommuCvExperienceAssures_relation' => array(self::HAS_MANY, 'OmmuCvExperienceAssure', 'member_id'),
			'ommuCvExperiences_relation' => array(self::HAS_MANY, 'OmmuCvExperiences', 'member_id'),
			'ommuCvOrganizations_relation' => array(self::HAS_MANY, 'OmmuCvOrganizations', 'member_id'),
			'ommuCvPortfolioAssures_relation' => array(self::HAS_MANY, 'OmmuCvPortfolioAssure', 'member_id'),
			'ommuCvPortfolioses_relation' => array(self::HAS_MANY, 'OmmuCvPortfolios', 'member_id'),
			'ommuCvPositiveNegatives_relation' => array(self::HAS_MANY, 'OmmuCvPositiveNegative', 'member_id'),
			'ommuCvReferences_relation' => array(self::HAS_MANY, 'OmmuCvReferences', 'member_id'),
			'ommuCvReferences1_relation' => array(self::HAS_MANY, 'OmmuCvReferences', 'referee_member_id'),
			'ommuCvSkillAssures_relation' => array(self::HAS_MANY, 'OmmuCvSkillAssure', 'member_id'),
			'ommuCvSkills_relation' => array(self::HAS_MANY, 'OmmuCvSkills', 'member_id'),
			'ommuCvTrainings_relation' => array(self::HAS_MANY, 'OmmuCvTrainings', 'member_id'),
			'ommuMemberCompanies_relation' => array(self::HAS_MANY, 'OmmuMemberCompany', 'member_id'),
			'ommuVacancies_relation' => array(self::HAS_MANY, 'OmmuVacancies', 'member_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'member_id' => Yii::t('attribute', 'Member'),
			'publish' => Yii::t('attribute', 'Publish'),
			'profile_id' => Yii::t('attribute', 'Profile'),
			'member_private' => Yii::t('attribute', 'Private'),
			'member_header' => Yii::t('attribute', 'Member Header'),
			'member_photo' => Yii::t('attribute', 'Member Photo'),
			'short_biography' => Yii::t('attribute', 'Short Biography'),
			'creation_date' => Yii::t('attribute', 'Creation Date'),
			'creation_id' => Yii::t('attribute', 'Creation'),
			'modified_date' => Yii::t('attribute', 'Modified Date'),
			'modified_id' => Yii::t('attribute', 'Modified'),
			'old_member_header_i' => Yii::t('attribute', 'Old Member Header'),
			'old_member_photo_i' => Yii::t('attribute', 'Old Member Photo'),
			'member_user_i' => Yii::t('attribute', 'Member User'),
			'member_search' => Yii::t('attribute', 'Member'),
			'creation_search' => Yii::t('attribute', 'Creation'),
			'modified_search' => Yii::t('attribute', 'Modified'),
			'user_search' => Yii::t('attribute', 'Users'),
			'like_search' => Yii::t('attribute', 'Likes'),
			'view_search' => Yii::t('attribute', 'Views'),
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.
		$controller = strtolower(Yii::app()->controller->id);

		$criteria=new CDbCriteria;
		
		// Custom Search
		$criteria->with = array(
			'view' => array(
				'alias'=>'view',
			),
			'creation' => array(
				'alias'=>'creation',
				'select'=>'displayname'
			),
			'modified' => array(
				'alias'=>'modified',
				'select'=>'displayname'
			),
		);

		$criteria->compare('t.member_id', $this->member_id);
		if(Yii::app()->getRequest()->getParam('type') == 'publish')
			$criteria->compare('t.publish', 1);
		elseif(Yii::app()->getRequest()->getParam('type') == 'unpublish')
			$criteria->compare('t.publish', 0);
		elseif(Yii::app()->getRequest()->getParam('type') == 'trash')
			$criteria->compare('t.publish', 2);
		else {
			$criteria->addInCondition('t.publish', array(0,1));
			$criteria->compare('t.publish', $this->publish);
		}
		if(Yii::app()->getRequest()->getParam('profile'))
			$criteria->compare('t.profile_id', Yii::app()->getRequest()->getParam('profile'));
		else
			$criteria->compare('t.profile_id', $this->profile_id);
		$criteria->compare('t.member_private', $this->member_private);
		$criteria->compare('t.member_header', strtolower($this->member_header), true);
		$criteria->compare('t.member_photo', strtolower($this->member_photo), true);
		$criteria->compare('t.short_biography', strtolower($this->short_biography), true);
		if($this->creation_date != null && !in_array($this->creation_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')))
			$criteria->compare('date(t.creation_date)', date('Y-m-d', strtotime($this->creation_date)));
		if(Yii::app()->getRequest()->getParam('creation'))
			$criteria->compare('t.creation_id', Yii::app()->getRequest()->getParam('creation'));
		else
			$criteria->compare('t.creation_id', $this->creation_id);
		if($this->modified_date != null && !in_array($this->modified_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')))
			$criteria->compare('date(t.modified_date)', date('Y-m-d', strtotime($this->modified_date)));
		if(Yii::app()->getRequest()->getParam('modified'))
			$criteria->compare('t.modified_id', Yii::app()->getRequest()->getParam('modified'));
		else
			$criteria->compare('t.modified_id', $this->modified_id);
		
		$criteria->compare('view.member_name', strtolower($this->member_search), true);
		$criteria->compare('creation.displayname', strtolower($this->creation_search), true);
		$criteria->compare('modified.displayname', strtolower($this->modified_search), true);
		$criteria->compare('view.users', $this->user_search);
		$criteria->compare('view.likes', $this->like_search);
		$criteria->compare('view.views', $this->view_search);

		if(!Yii::app()->getRequest()->getParam('Members_sort'))
			$criteria->order = 't.member_id DESC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
				'pageSize'=>30,
			),
		));
	}


	/**
	 * Get column for CGrid View
	 */
	public function getGridColumn($columns=null) {
		if($columns !== null) {
			foreach($columns as $val) {
				/*
				if(trim($val) == 'enabled') {
					$this->defaultColumns[] = array(
						'name'  => 'enabled',
						'value' => '$data->enabled == 1? "Ya": "Tidak"',
					);
				}
				*/
				$this->defaultColumns[] = $val;
			}
		} else {
			//$this->defaultColumns[] = 'member_id';
			$this->defaultColumns[] = 'publish';
			$this->defaultColumns[] = 'profile_id';
			$this->defaultColumns[] = 'member_private';
			$this->defaultColumns[] = 'member_header';
			$this->defaultColumns[] = 'member_photo';
			$this->defaultColumns[] = 'short_biography';
			$this->defaultColumns[] = 'creation_date';
			$this->defaultColumns[] = 'creation_id';
			$this->defaultColumns[] = 'modified_date';
			$this->defaultColumns[] = 'modified_id';
		}

		return $this->defaultColumns;
	}

	/**
	 * Set default columns to display
	 */
	protected function afterConstruct() {
		$controller = strtolower(Yii::app()->controller->id);
		
		if(count($this->defaultColumns) == 0) {
			/*
			$this->defaultColumns[] = array(
				'class' => 'CCheckBoxColumn',
				'name' => 'id',
				'selectableRows' => 2,
				'checkBoxHtmlOptions' => array('name' => 'trash_id[]')
			);
			*/
			$this->defaultColumns[] = array(
				'header' => 'No',
				'value' => '$this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize + $row+1'
			);
			if($controller == 'o/admin' && !Yii::app()->getRequest()->getParam('profile')) {
				$this->defaultColumns[] = array(
					'name' => 'profile_id',
					'value' => 'Phrase::trans($data->profile->profile_name)',
					'filter'=>MemberProfile::getProfile(),
				);		
			}
			$this->defaultColumns[] = array(
				'name' => 'member_search',
				'value' => '$data->view->member_name',
			);
			//$this->defaultColumns[] = 'member_header';
			//$this->defaultColumns[] = 'member_photo';
			//$this->defaultColumns[] = 'short_biography';
			$this->defaultColumns[] = array(
				'name' => 'creation_search',
				'value' => '$data->creation->displayname',
			);
			$this->defaultColumns[] = array(
				'name' => 'creation_date',
				'value' => 'Yii::app()->dateFormatter->formatDateTime($data->creation_date, \'medium\', false)',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => $this->filterDatepicker($this, 'creation_date'),
			);
			$this->defaultColumns[] = array(
				'name' => 'user_search',
				'value' => 'CHtml::link($data->view->users, Yii::app()->controller->createUrl("o/user/manage", array(\'member\'=>$data->member_id,\'type\'=>\'publish\')))',
				'htmlOptions' => array(
					'class' => 'center',
				),	
				'type' => 'raw',
			);
			$this->defaultColumns[] = array(
				'name' => 'like_search',
				'value' => 'CHtml::link($data->view->likes != null ? $data->view->likes : \'0\', Yii::app()->controller->createUrl("o/like/manage", array(\'member\'=>$data->member_id,\'type\'=>\'publish\')))',
				'htmlOptions' => array(
					'class' => 'center',
				),	
				'type' => 'raw',
			);
			$this->defaultColumns[] = array(
				'name' => 'view_search',
				'value' => 'CHtml::link($data->view->views != null ? $data->view->views : \'0\', Yii::app()->controller->createUrl("o/views/manage", array(\'member\'=>$data->member_id,\'type\'=>\'publish\')))',
				'htmlOptions' => array(
					'class' => 'center',
				),	
				'type' => 'raw',
			);
			$this->defaultColumns[] = array(
				'name' => 'member_private',
				'value' => 'Utility::getPublish(Yii::app()->controller->createUrl("private", array("id"=>$data->member_id)), $data->member_private, 1)',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => $this->filterYesNo(),
				'type' => 'raw',
			);
			if(!Yii::app()->getRequest()->getParam('type')) {
				$this->defaultColumns[] = array(
					'name' => 'publish',
					'value' => 'Utility::getPublish(Yii::app()->controller->createUrl("publish", array("id"=>$data->member_id)), $data->publish, 1)',
					'htmlOptions' => array(
						'class' => 'center',
					),
					'filter' => $this->filterYesNo(),
					'type' => 'raw',
				);
			}
		}
		parent::afterConstruct();
	}

	/**
	 * User get information
	 */
	public static function getInfo($id, $column=null)
	{
		if($column != null) {
			$model = self::model()->findByPk($id, array(
				'select' => $column,
			));
 			if(count(explode(',', $column)) == 1)
 				return $model->$column;
 			else
 				return $model;
			
		} else {
			$model = self::model()->findByPk($id);
			return $model;
		}
	}

	/**
	 * Resize Photo
	 */
	public static function resizePhoto($photo, $resize) {
		Yii::import('ext.phpthumb.PhpThumbFactory');
		$resizePhoto = PhpThumbFactory::create($photo, array('jpegQuality' => 90, 'correctPermissions' => true));
		if($resize['height'] == 0)
			$resizePhoto->resize($resize['width']);
		else			
			$resizePhoto->adaptiveResize($resize['width'], $resize['height']);
		$resizePhoto->save($photo);
		
		return true;
	}

	/**
	 * before validate attributes
	 */
	protected function beforeValidate() {
		$setting = MemberSetting::model()->findByPk(1, array(
			'select' => 'photo_file_type',
		));
		$photo_file_type = unserialize($setting->photo_file_type);
		if(empty($photo_file_type))
			$photo_file_type = array();
		
		if(parent::beforeValidate()) {		
			if($this->isNewRecord)
				$this->creation_id = Yii::app()->user->id;	
			else
				$this->modified_id = Yii::app()->user->id;
			
			$member_header = CUploadedFile::getInstance($this, 'member_header');
			if($member_header != null) {
				$extension = pathinfo($member_header->name, PATHINFO_EXTENSION);
				if(!in_array(strtolower($extension), $photo_file_type))
					$this->addError('member_header', Yii::t('phrase', 'The file {name} cannot be uploaded. Only files with these extensions are allowed: {extensions}.', array(
						'{name}'=>$member_header->name,
						'{extensions}'=>Utility::formatFileType($photo_file_type, false),
					)));
			}
			
			$member_photo = CUploadedFile::getInstance($this, 'member_photo');
			if($member_photo != null) {
				$extension = pathinfo($member_photo->name, PATHINFO_EXTENSION);
				if(!in_array(strtolower($extension), $photo_file_type))
					$this->addError('member_photo', Yii::t('phrase', 'The file {name} cannot be uploaded. Only files with these extensions are allowed: {extensions}.', array(
						'{name}'=>$member_photo->name,
						'{extensions}'=>Utility::formatFileType($photo_file_type, false),
					)));
			}
		}
		return true;
	}
	
	/**
	 * before save attributes
	 */
	protected function beforeSave() 
	{		
		$setting = MemberSetting::model()->findByPk(1, array(
			'select' => 'photo_resize, photo_resize_size',
		));
		$photo_resize_size = unserialize($setting->photo_resize_size);
		
		if(parent::beforeSave()) {			
			if(!$this->isNewRecord) {
				$member_path = 'public/member/'.$this->member_id;
				
				// Add directory
				if(!file_exists($member_path)) {
					@mkdir($member_path, 0755, true);

					// Add file in directory (index.php)
					$newFile = $member_path.'/index.php';
					$FileHandle = fopen($newFile, 'w');
				} else
					@chmod($member_path, 0755, true);
				
				$this->member_header = CUploadedFile::getInstance($this, 'member_header');
				if($this->member_header != null) {
					if($this->member_header instanceOf CUploadedFile) {
						$fileName = time().'_header_'.$this->urlTitle($this->view->member_name).'.'.strtolower($this->member_header->extensionName);
						if($this->member_header->saveAs($member_path.'/'.$fileName)) {							
							if($this->old_member_header_i != '' && file_exists($member_path.'/'.$this->old_member_header_i))
								rename($member_path.'/'.$this->old_member_header_i, 'public/member/verwijderen/'.$this->member_id.'_'.$this->old_member_header_i);
							$this->member_header = $fileName;
						}
					}
				} else {
					if($this->member_header == '')
						$this->member_header = $this->old_member_header_i;
				}
				
				$this->member_photo = CUploadedFile::getInstance($this, 'member_photo');
				if($this->member_photo != null) {
					if($this->member_photo instanceOf CUploadedFile) {
						$fileName = time().'_header_'.$this->urlTitle($this->view->member_name).'.'.strtolower($this->member_photo->extensionName);
						if($this->member_photo->saveAs($member_path.'/'.$fileName)) {							
							if($this->old_member_photo_i != '' && file_exists($member_path.'/'.$this->old_member_photo_i))
								rename($member_path.'/'.$this->old_member_photo_i, 'public/member/verwijderen/'.$this->member_id.'_'.$this->old_member_photo_i);
							$this->member_photo = $fileName;
							
							if($setting->photo_resize == 1)
								self::resizePhoto($member_path.'/'.$fileName, $photo_resize_size);
						}
					}
				} else {
					if($this->member_photo == '')
						$this->member_photo = $this->old_member_photo_i;
				}
			}
		}
		return true;
	}
	
	/**
	 * After save attributes
	 */
	protected function afterSave() {
		parent::afterSave();
		
		$setting = MemberSetting::model()->findByPk(1, array(
			'select' => 'photo_resize, photo_resize_size',
		));
		$photo_resize_size = unserialize($setting->photo_resize_size);
		
		if($this->isNewRecord) {
			$member_path = 'public/member/'.$this->member_id;
			
			// Add directory
			if(!file_exists($member_path)) {
				@mkdir($member_path, 0755, true);

				// Add file in directory (index.php)
				$newFile = $member_path.'/index.php';
				$FileHandle = fopen($newFile, 'w');
			} else
				@chmod($member_path, 0755, true);
			
			$this->member_header = CUploadedFile::getInstance($this, 'member_header');
			if($this->member_header != null) {
				if($this->member_header instanceOf CUploadedFile) {
					$fileName = time().'_header_'.$this->urlTitle($this->view->member_name).'.'.strtolower($this->member_header->extensionName);
					if($this->member_header->saveAs($member_path.'/'.$fileName))
						self::model()->updateByPk($this->member_id, array('member_header'=>$fileName));
				}
			}
			
			$this->member_photo = CUploadedFile::getInstance($this, 'member_photo');
			if($this->member_photo != null) {
				if($this->member_photo instanceOf CUploadedFile) {
					$fileName = time().'_photo_'.$this->urlTitle($this->view->member_name).'.'.strtolower($this->member_photo->extensionName);
					if($this->member_photo->saveAs($member_path.'/'.$fileName)) {
						if($setting->photo_resize == 1)
							self::resizePhoto($member_path.'/'.$fileName, $photo_resize_size);
						self::model()->updateByPk($this->member_id, array('member_photo'=>$fileName));
					}
				}
			}
		}
	}

	/**
	 * After delete attributes
	 */
	protected function afterDelete() {
		parent::afterDelete();
		//delete member photo
		$member_path = 'public/member/'.$this->member_id;
		
		if($this->member_header != '' && file_exists($member_path.'/'.$this->member_header))
			rename($member_path.'/'.$this->member_header, 'public/member/verwijderen/'.$this->member_id.'_'.$this->member_header);
		
		if($this->member_photo != '' && file_exists($member_path.'/'.$this->member_photo))
			rename($member_path.'/'.$this->member_photo, 'public/member/verwijderen/'.$this->member_id.'_'.$this->member_photo);
	}

}