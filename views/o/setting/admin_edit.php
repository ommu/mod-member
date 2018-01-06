<?php
/**
 * Member Settings (member-setting)
 * @var $this SettingController
 * @var $model MemberSetting
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (opensource.ommu.co)
 * @created date 2 March 2017, 10:26 WIB
 * @link https://github.com/ommu/ommu-member
 *
 */

	$this->breadcrumbs=array(
		'Member Settings'=>array('manage'),
		$model->id=>array('view','id'=>$model->id),
		'Update',
	);
?>

<div class="form" name="post-on">
	<?php echo $this->renderPartial('_form', array(
		'model'=>$model,
		'member'=>$member,
	)); ?>
</div>
