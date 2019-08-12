<?php
/**
 * Member Companies (member-company)
 * @var $this CompanyController
 * @var $model MemberCompany
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @created date 9 March 2017, 11:59 WIB
 * @link https://github.com/ommu/mod-member
 *
 */

	$this->breadcrumbs=array(
		'Member Companies'=>array('manage'),
		'Publish',
	);
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'member-company-form',
	'enableAjaxValidation'=>true,
)); ?>

	<div class="dialog-content">
		<?php echo $model->publish == 1 ? Yii::t('phrase', 'Are you sure you want to unpublish this item?') : Yii::t('phrase', 'Are you sure you want to publish this item?')?>
	</div>
	<div class="dialog-submit">
		<?php echo CHtml::submitButton($title, array('onclick' => 'setEnableSave()')); ?>
		<?php echo CHtml::button(Yii::t('phrase', 'Cancel'), array('id'=>'closed')); ?>
	</div>
	
<?php $this->endWidget(); ?>
