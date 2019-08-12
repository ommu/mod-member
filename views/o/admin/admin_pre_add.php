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
?>

<?php $form=$this->beginWidget('application.libraries.yii-traits.system.OActiveForm', array(
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
			<?php echo $form->errorSummary($users); ?>
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
						echo $form->dropDownList($model,'profile_id', $profile, array('prompt'=>Yii::t('phrase', 'Select Profile')));
					else
						echo $form->dropDownList($model,'profile_id', array('prompt'=>Yii::t('phrase', 'Select Profile')));?>
				<?php } else {?>
					<strong><?php echo Phrase::trans($model->profile->profile_name);?></strong>
				<?php }?>
				<?php echo $form->error($model,'profile_id'); ?>
				<?php /*<div class="small-px silent"></div>*/?>
			</div>
		</div>
		
		<div class="clearfix">
			<?php echo $form->labelEx($model,'member_user_i'); ?>
			<div class="desc">
				<?php //echo $form->textField($model,'member_user_i', array('maxlength'=>32,'class'=>'span-6'));
				$this->widget('zii.widgets.jui.CJuiAutoComplete', array(
					'model' => $model,
					'attribute' => 'member_user_i',
					'source' => Yii::app()->controller->createUrl('o/user/suggest', array('data'=>'member')),
					'options' => array(
						//'delay '=> 50,
						'minLength' => 1,
						'showAnim' => 'fold',
						'select' => "js:function(event, ui) {
							$('#Members_member_user_i').val(ui.item.value);
						}"
					),
					'htmlOptions' => array(
						'class'	=> 'span-6',
					),
				));
				echo $form->error($model,'member_user_i');?>
			</div>
		</div>

	</fieldset>
</div>
<div class="dialog-submit">
	<?php echo CHtml::submitButton(Yii::t('phrase', 'Next') , array('onclick' => 'setEnableSave()')); ?>
	<?php echo CHtml::button(Yii::t('phrase', 'Cancel'), array('id'=>'closed')); ?>
</div>
<?php $this->endWidget(); ?>


