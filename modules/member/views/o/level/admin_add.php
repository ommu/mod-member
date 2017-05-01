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
 * @link https://github.com/ommu/Members
 * @contact (+62)856-299-4114
 *
 */

	$this->breadcrumbs=array(
		'Member Levels'=>array('manage'),
		'Create',
	);
?>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>