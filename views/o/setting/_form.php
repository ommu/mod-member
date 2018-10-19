<?php
/**
 * Member Settings (member-setting)
 * @var $this SettingController
 * @var $model MemberSetting
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @created date 2 March 2017, 10:26 WIB
 * @link https://github.com/ommu/mod-member
 *
 */

	$cs = Yii::app()->getClientScript();
$js=<<<EOP
	$('input[name="MemberSetting[photo_resize]"]').on('change', function() {
		var id = $(this).val();
		if(id == '1') {
			$('div#resize_size').slideDown();
		} else {
			$('div#resize_size').slideUp();
		}
	});
EOP;
	$cs->registerScript('resize', $js, CClientScript::POS_END);
?>

<?php $form=$this->beginWidget('application.libraries.yii-traits.system.OActiveForm', array(
	'id'=>'member-setting-form',
	'enableAjaxValidation'=>true,
)); ?>

<?php //begin.Messages ?>
<div id="ajax-message">
	<?php echo $form->errorSummary($model); ?>
</div>
<?php //begin.Messages ?>

<fieldset>

	<div class="clearfix">
		<label>
			<?php echo $model->getAttributeLabel('license');?> <span class="required">*</span><br/>
			<span><?php echo Yii::t('phrase', 'Enter the your license key that is provided to you when you purchased this plugin. If you do not know your license key, please contact support team.');?></span>
		</label>
		<div class="desc">
			<?php if($model->isNewRecord || (!$model->isNewRecord && $model->license == '')) {
				$model->license = $this->licenseCode();
				echo $form->textField($model,'license', array('maxlength'=>32,'class'=>'span-4'));
			} else
				echo $form->textField($model,'license', array('maxlength'=>32,'class'=>'span-4','disabled'=>'disabled'));?>
			<?php echo $form->error($model,'license'); ?>
			<div class="small-px"><?php echo Yii::t('phrase', 'Format: XXXX-XXXX-XXXX-XXXX');?></div>
		</div>
	</div>

	<div class="clearfix">
		<?php echo $form->labelEx($model,'permission'); ?>
		<div class="desc">
			<div class="small-px"><?php echo Yii::t('phrase', 'Select whether or not you want to let the public (visitors that are not logged-in) to view the following sections of your social network. In some cases (such as Profiles, Blogs, and Albums), if you have given them the option, your users will be able to make their pages private even though you have made them publically viewable here. For more permissions settings, please visit the General Settings page.');?></div>
			<?php echo $form->radioButtonList($model, 'permission', array(
				1 => Yii::t('phrase', 'Yes, the public can view members unless they are made private.'),
				0 => Yii::t('phrase', 'No, the public cannot view members.'),
			)); ?>
			<?php echo $form->error($model,'permission'); ?>
		</div>
	</div>

	<div class="clearfix">
		<?php echo $form->labelEx($model,'meta_description'); ?>
		<div class="desc">
			<?php echo $form->textArea($model,'meta_description', array('rows'=>6, 'cols'=>50, 'class'=>'span-7 smaller')); ?>
			<?php echo $form->error($model,'meta_description'); ?>
		</div>
	</div>

	<div class="clearfix">
		<?php echo $form->labelEx($model,'meta_keyword'); ?>
		<div class="desc">
			<?php echo $form->textArea($model,'meta_keyword', array('rows'=>6, 'cols'=>50, 'class'=>'span-7 smaller')); ?>
			<?php echo $form->error($model,'meta_keyword'); ?>
		</div>
	</div>

	<div class="clearfix">
		<?php echo $form->labelEx($model,'default_user_level'); ?>
		<div class="desc">
			<?php 
			$userlevel = UserLevel::getLevel();
			if($userlevel != null)
				echo $form->dropDownList($model,'default_user_level', $userlevel, array('prompt'=>Yii::t('phrase', 'Select Userlevel')));
			else
				echo $form->dropDownList($model,'default_user_level', array('prompt'=>Yii::t('phrase', 'Select Userlevel')));?>
			<?php echo $form->error($model,'default_user_level'); ?>
			<?php /*<div class="small-px silent"></div>*/?>
		</div>
	</div>

	<div class="clearfix">
		<?php echo $form->labelEx($model,'default_member_level'); ?>
		<div class="desc">
			<?php 
			$memberlevel = MemberLevels::getLevel();
			if($memberlevel != null)
				echo $form->dropDownList($model,'default_member_level', $memberlevel, array('prompt'=>Yii::t('phrase', 'Select Member Level')));
			else
				echo $form->dropDownList($model,'default_member_level', array('prompt'=>Yii::t('phrase', 'Select Member Level')));?>
			<?php echo $form->error($model,'default_member_level'); ?>
			<?php /*<div class="small-px silent"></div>*/?>
		</div>
	</div>

	<div class="clearfix">
		<label><?php echo $model->getAttributeLabel('form_custom_insert_field');?></label>
		<div class="desc">
			<?php 				
			$customField = array(
				'member_header' => $member->getAttributeLabel('member_header'),
				'member_photo' => $member->getAttributeLabel('member_photo'),
				'short_biography' => $member->getAttributeLabel('short_biography'),
			);
			if(!$model->getErrors())
				$model->form_custom_insert_field = unserialize($model->form_custom_insert_field);
			
			echo $form->checkBoxList($model, 'form_custom_insert_field', $customField); ?>
			<?php echo $form->error($model,'form_custom_insert_field'); ?>
		</div>
	</div>

	<div class="clearfix">
		<?php echo $form->labelEx($model,'photo_limit'); ?>
		<div class="desc">
			<?php echo $form->textField($model,'photo_limit', array('maxlength'=>2, 'class'=>'span-2')); ?>
			<?php echo $form->error($model,'photo_limit'); ?>
		</div>
	</div>

	<div class="clearfix">
		<label><?php echo Yii::t('phrase', 'Photo Setting');?> <span class="required">*</span></label>
		<div class="desc">
			<p><?php echo $model->getAttributeLabel('photo_resize');?></p>
			<?php echo $form->radioButtonList($model, 'photo_resize', array(
				0 => Yii::t('phrase', 'No, not resize photo after upload.'),
				1 => Yii::t('phrase', 'Yes, resize photo after upload.'),
			)); ?>
			
			<?php if(!$model->getErrors()) {
				$model->photo_resize_size = unserialize($model->photo_resize_size);
				$model->photo_view_size = unserialize($model->photo_view_size);
			}?>
			
			<div id="resize_size" class="mt-15 <?php echo $model->photo_resize == 0 ? 'hide' : '';?>">
				<?php echo Yii::t('phrase', 'Width').': ';?><?php echo $form->textField($model,'photo_resize_size[width]', array('maxlength'=>4,'class'=>'span-2')); ?>&nbsp;&nbsp;&nbsp;
				<?php echo Yii::t('phrase', 'Height').': ';?><?php echo $form->textField($model,'photo_resize_size[height]', array('maxlength'=>4,'class'=>'span-2')); ?>
				<?php echo $form->error($model,'photo_resize_size'); ?>
			</div>
			
			<p><?php echo Yii::t('phrase', 'Large Size');?></p>
			<?php echo Yii::t('phrase', 'Width').': ';?><?php echo $form->textField($model,'photo_view_size[large][width]', array('maxlength'=>4,'class'=>'span-2')); ?>&nbsp;&nbsp;&nbsp;
			<?php echo Yii::t('phrase', 'Height').': ';?><?php echo $form->textField($model,'photo_view_size[large][height]', array('maxlength'=>4,'class'=>'span-2')); ?>
			<?php echo $form->error($model,'photo_view_size[large]'); ?>
			
			<p><?php echo Yii::t('phrase', 'Medium Size');?></p>
			<?php echo Yii::t('phrase', 'Width').': ';?><?php echo $form->textField($model,'photo_view_size[medium][width]', array('maxlength'=>3,'class'=>'span-2')); ?>&nbsp;&nbsp;&nbsp;
			<?php echo Yii::t('phrase', 'Height').': ';?><?php echo $form->textField($model,'photo_view_size[medium][height]', array('maxlength'=>3,'class'=>'span-2')); ?>
			<?php echo $form->error($model,'photo_view_size[medium]'); ?>
			
			<p><?php echo Yii::t('phrase', 'Small Size');?></p>
			<?php echo Yii::t('phrase', 'Width').': ';?><?php echo $form->textField($model,'photo_view_size[small][width]', array('maxlength'=>3,'class'=>'span-2')); ?>&nbsp;&nbsp;&nbsp;
			<?php echo Yii::t('phrase', 'Height').': ';?><?php echo $form->textField($model,'photo_view_size[small][height]', array('maxlength'=>3,'class'=>'span-2')); ?>
			<?php echo $form->error($model,'photo_view_size[small]'); ?>
		</div>
	</div>

	<div class="clearfix">
		<?php echo $form->labelEx($model,'photo_file_type'); ?>
		<div class="desc">
			<?php 
			if(!$model->getErrors()) {
				$photo_file_type = unserialize($model->photo_file_type);
				if(!empty($photo_file_type))
					$model->photo_file_type = Utility::formatFileType($photo_file_type, false);
			}
			echo $form->textField($model,'photo_file_type', array('class'=>'span-8')); ?>
			<?php echo $form->error($model,'photo_file_type'); ?>
			<div class="small-px">pisahkan jenis file dengan koma (,). example: "jpg, png, bmp"</div>
		</div>
	</div>

	<div class="submit clearfix">
		<label>&nbsp;</label>
		<div class="desc">
			<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('phrase', 'Create') : Yii::t('phrase', 'Save'), array('onclick' => 'setEnableSave()')); ?>
		</div>
	</div>

</fieldset>
<?php $this->endWidget(); ?>


