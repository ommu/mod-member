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
?>

<?php $form=$this->beginWidget('application.components.system.OActiveForm', array(
	'id'=>'members-form',
	'enableAjaxValidation'=>true,
	'htmlOptions' => array(
		'enctype' => 'multipart/form-data',
		'on_post' => true,
	),
)); ?>

<div class="dialog-content">
	<fieldset>
		<?php //begin.Messages ?>
		<?php /*
		<div id="ajax-message">
			<?php echo $form->errorSummary($model); ?>
		</div>
		*/?>
		<?php //begin.Messages ?>

		<div class="clearfix">
			<?php echo $form->labelEx($model,'profile_id'); ?>
			<div class="desc">
				<?php if($model->isNewRecord) {?>
					<?php //echo $form->textField($model,'profile_id');
					$profile = MemberProfile::getProfile();
					if(!empty($profile))
						echo $form->dropDownList($model,'profile_id', $profile);
					else
						echo $form->dropDownList($model,'profile_id', array('prompt'=>Yii::t('phrase', 'Select Profile')));?>
				<?php } else {?>
					<strong><?php echo Phrase::trans($data->profile_name);?></strong>
				<?php }?>
				<?php echo $form->error($model,'profile_id'); ?>
				<?php /*<div class="small-px silent"></div>*/?>
			</div>
		</div>

		<div class="clearfix">
			<?php echo $form->labelEx($model,'member_header'); ?>
			<div class="desc">
				<?php echo $form->fileField($model,'member_header'); ?>
				<?php echo $form->error($model,'member_header'); ?>
				<?php /*<div class="small-px silent"></div>*/?>
			</div>
		</div>

		<div class="clearfix">
			<?php echo $form->labelEx($model,'member_photo'); ?>
			<div class="desc">
				<?php echo $form->fileField($model,'member_photo'); ?>
				<?php echo $form->error($model,'member_photo'); ?>
				<?php /*<div class="small-px silent"></div>*/?>
			</div>
		</div>

		<div class="clearfix">
			<?php echo $form->labelEx($model,'short_biography'); ?>
			<div class="desc">
				<?php echo $form->textArea($model,'short_biography',array('maxlength'=>160, 'rows'=>6, 'cols'=>50, 'class'=>'span-10 smaller')); ?>
				<?php echo $form->error($model,'short_biography'); ?>
				<?php /*<div class="small-px silent"></div>*/?>
			</div>
		</div>

		<div class="clearfix publish">
			<?php echo $form->labelEx($model,'publish'); ?>
			<div class="desc">
				<?php echo $form->checkBox($model,'publish'); ?>
				<?php echo $form->labelEx($model,'publish'); ?>
				<?php echo $form->error($model,'publish'); ?>
				<?php /*<div class="small-px silent"></div>*/?>
			</div>
		</div>

	</fieldset>
</div>
<div class="dialog-submit">
	<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('phrase', 'Create') : Yii::t('phrase', 'Save') ,array('onclick' => 'setEnableSave()')); ?>
	<?php echo CHtml::button(Yii::t('phrase', 'Cancel'), array('id'=>'closed')); ?>
</div>
<?php $this->endWidget(); ?>


