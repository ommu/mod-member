<?php
/**
 * Members (members)
 * @var $this AdminController
 * @var $model Members
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (opensource.ommu.co)
 * @created date 2 March 2017, 15:02 WIB
 * @link https://github.com/ommu/ommu-member
 *
 */

	$this->breadcrumbs=array(
		'Members'=>array('manage'),
		'Create',
	);
?>

<?php echo $this->renderPartial('_form', $data); ?>