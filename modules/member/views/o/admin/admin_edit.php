<?php
/**
 * Members (members)
 * @var $this AdminController
 * @var $model Members
 * @var $form CActiveForm
 * version: 0.0.1
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2017 Ommu Platform (opensource.ommu.co)
 * @created date 2 March 2017, 15:02 WIB
 * @link https://github.com/ommu/Members
 * @contect (+62)856-299-4114
 *
 */

	$this->breadcrumbs=array(
		'Members'=>array('manage'),
		$model->member_id=>array('view','id'=>$model->member_id),
		'Update',
	);
?>

<?php echo $this->renderPartial('_form', array(
	'model'=>$model,
	'condition'=>$condition,
	'users'=>$users,
	'setting'=>$setting,
	'form_custom'=>$form_custom,
)); ?>