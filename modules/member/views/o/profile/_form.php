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
 * @link https://github.com/ommu/Members
 * @contact (+62)856-299-4114
 *
 */

	$cs = Yii::app()->getClientScript();
$js=<<<EOP
	$('#MemberProfile_multiple_user').on('change', function() {
		var id = $(this).prop('checked');		
		if(id == true) {
			$('div#multiple-user').slideDown();
		} else {
			$('div#multiple-user').slideUp();
		}
	});
	$('#MemberProfile_user_unlimit_i').on('change', function() {
		var id = $(this).prop('checked');		
		if(id == true) {
			$('div#user-limit').slideUp();
		} else {
			$('div#user-limit').slideDown();
		}
	});
EOP;
	$cs->registerScript('user-limit', $js, CClientScript::POS_END);
?>

<?php $form=$this->beginWidget('application.components.system.OActiveForm', array(
	'id'=>'member-profile-form',
	'enableAjaxValidation'=>true,
	//'htmlOptions' => array('enctype' => 'multipart/form-data')
)); ?>

<div class="dialog-content">
	<fieldset>
		<?php //begin.Messages ?>
		<div id="ajax-message">
			<?php echo $form->errorSummary($model); ?>
		</div>
		<?php //begin.Messages ?>

		<div class="clearfix">
			<?php echo $form->labelEx($model,'title'); ?>
			<div class="desc">
				<?php 
				$model->title = Phrase::trans($model->profile_name);
				echo $form->textField($model,'title',array('maxlength'=>32,'class'=>'span-8')); ?>
				<?php echo $form->error($model,'title'); ?>
			</div>
		</div>

		<div class="clearfix">
			<?php echo $form->labelEx($model,'description'); ?>
			<div class="desc">
				<?php 
				$model->description = Phrase::trans($model->profile_desc);
				echo $form->textArea($model,'description',array('maxlength'=>128,'class'=>'span-11 smaller')); ?>
				<?php echo $form->error($model,'description'); ?>
			</div>
		</div>

		<div class="clearfix publish">
			<?php echo $form->labelEx($model,'multiple_user'); ?>
			<div class="desc">
				<?php echo $form->checkBox($model,'multiple_user'); ?>
				<?php echo $form->labelEx($model,'multiple_user'); ?>
				<?php echo $form->error($model,'multiple_user'); ?>
				<?php /*<div class="small-px silent"></div>*/?>
			</div>
		</div>

		<div id="multiple-user" class="<?php echo $model->multiple_user == 0 ? 'hide' : '';?>">
			<div class="clearfix">
				<?php echo $form->labelEx($model,'user_unlimit_i'); ?>
				<div class="desc">
					<?php 
					if(!$model->getErrors())
						$model->user_unlimit_i = $model->user_limit == 0 ? 1 : 0;
					echo $form->checkBox($model,'user_unlimit_i'); ?>
					<?php echo $form->error($model,'user_unlimit_i'); ?>
				</div>
			</div>
			
			<div id="user-limit" class="clearfix <?php echo $model->user_limit == 0 ? 'hide' : '';?>">
				<?php echo $form->labelEx($model,'user_limit'); ?>
				<div class="desc">
					<?php echo $form->textField($model,'user_limit',array('maxlength'=>5,'class'=>'span-4')); ?>
					<?php echo $form->error($model,'user_limit'); ?>
				</div>
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


