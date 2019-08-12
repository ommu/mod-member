<?php
/**
 * Members (members)
 * @var $this AdminController
 * @var $model Members
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @created date 2 March 2017, 15:02 WIB
 * @link https://github.com/ommu/mod-member
 *
 */

	$this->breadcrumbs=array(
		'Members'=>array('manage'),
		$model->member_id=>array('view','id'=>$model->member_id),
		Yii::t('phrase', 'Update'),
	);
?>

<?php echo $this->renderPartial('_form', $data); ?>