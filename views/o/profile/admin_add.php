<?php
/**
 * Member Profiles (member-profile)
 * @var $this ProfileController
 * @var $model MemberProfile
 * @var $form CActiveForm
 * version: 0.0.1
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2017 Ommu Platform (opensource.ommu.co)
 * @created date 2 March 2017, 09:52 WIB
 * @link https://github.com/ommu/ommu-member
 * @contact (+62)856-299-4114
 *
 */

	$this->breadcrumbs=array(
		'Member Profiles'=>array('manage'),
		'Create',
	);
?>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>