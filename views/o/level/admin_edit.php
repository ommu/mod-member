<?php
/**
 * Member Levels (member-levels)
 * @var $this LevelController
 * @var $model MemberLevels
 * @var $form CActiveForm
 * version: 0.0.1
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2017 Ommu Platform (opensource.ommu.co)
 * @created date 8 March 2017, 22:24 WIB
 * @link https://github.com/ommu/ommu-member
 * @contact (+62)856-299-4114
 *
 */

	$this->breadcrumbs=array(
		'Member Levels'=>array('manage'),
		$model->level_id=>array('view','id'=>$model->level_id),
		'Update',
	);
?>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>