<?php
/**
 * Member Profiles (member-profile)
 * @var $this ProfileController
 * @var $model MemberProfile
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @created date 2 March 2017, 09:52 WIB
 * @link https://github.com/ommu/mod-member
 *
 */

	$this->breadcrumbs=array(
		'Member Profiles'=>array('manage'),
		Yii::t('phrase', 'Create'),
	);
?>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>