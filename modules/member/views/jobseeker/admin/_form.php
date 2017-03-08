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
			<?php echo $form->errorSummary($users); ?>
		</div>
		*/?>
		<?php //begin.Messages ?>

		<div class="clearfix">
			<?php echo $form->labelEx($model,'profile_id'); ?>
			<div class="desc">
				<?php if($model->isNewRecord) {?>
					<?php //echo $form->textField($model,'profile_id');
					$profile = MemberProfile::getProfile(null, '0');
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

		<?php if(!$model->isNewRecord || ($model->isNewRecord && in_array('member_header', $form_custom))) {?>
		<div class="clearfix">
			<?php echo $form->labelEx($model,'member_header'); ?>
			<div class="desc">
				<?php 
				if(!$model->getErrors())
					$model->old_member_header_i = $model->member_header;
				if(!$model->isNewRecord && $model->old_member_header_i != '') {
					echo $form->hiddenField($model,'old_member_header_i');
					$headerPhoto = Yii::app()->request->baseUrl.'/public/member/'.$model->member_id.'/'.$model->old_member_header_i;?>
						<img class="mb-10" src="<?php echo Utility::getTimThumb($headerPhoto, 400, 250, 1);?>" alt="">
				<?php }
				echo $form->fileField($model,'member_header'); ?>
				<?php echo $form->error($model,'member_header'); ?>
				<?php /*<div class="small-px silent"></div>*/?>
			</div>
		</div>
		<?php }?>

		<?php if(!$model->isNewRecord || ($model->isNewRecord && in_array('member_photo', $form_custom))) {?>
		<div class="clearfix">
			<?php echo $form->labelEx($model,'member_photo'); ?>
			<div class="desc">
				<?php 
				if(!$model->getErrors())
					$model->old_member_photo_i = $model->member_photo;
				if(!$model->isNewRecord && $model->old_member_photo_i != '') {
					echo $form->hiddenField($model,'old_member_photo_i');
					$photo = Yii::app()->request->baseUrl.'/public/member/'.$model->member_id.'/'.$model->old_member_photo_i;?>
						<img class="mb-10" src="<?php echo Utility::getTimThumb($photo, 400, 250, 1);?>" alt="">
				<?php }
				echo $form->fileField($model,'member_photo'); ?>
				<?php echo $form->error($model,'member_photo'); ?>
				<?php /*<div class="small-px silent"></div>*/?>
			</div>
		</div>
		<?php }?>

		<?php if(!$model->isNewRecord || ($model->isNewRecord && in_array('short_biography', $form_custom))) {?>
		<div class="clearfix">
			<?php echo $form->labelEx($model,'short_biography'); ?>
			<div class="desc">
				<?php echo $form->textArea($model,'short_biography',array('maxlength'=>160, 'rows'=>6, 'cols'=>50, 'class'=>'span-10 smaller')); ?>
				<?php echo $form->error($model,'short_biography'); ?>
				<?php /*<div class="small-px silent"></div>*/?>
			</div>
		</div>
		<?php }?>

		<div class="clearfix">
			<?php echo $form->labelEx($users,'displayname'); ?>
			<div class="desc">
				<?php echo $form->textField($users,'displayname',array('maxlength'=>64,'class'=>'span-7')); ?>
				<?php echo $form->error($users,'displayname'); ?>
				<?php /*<div class="small-px silent"></div>*/?>
			</div>
		</div>

		<?php if($setting->signup_username == 1) {?>
		<div class="clearfix">
			<label><?php echo $users->getAttributeLabel('username')?> <span class="required">*</span></label>
			<div class="desc">
				<?php echo $form->textField($users,'username',array('maxlength'=>32,'class'=>'span-7')); ?>
				<?php echo $form->error($users,'username'); ?>
			</div>
		</div>
		<?php }?>

		<div class="clearfix">
			<?php echo $form->labelEx($users,'email'); ?>
			<div class="desc">
				<?php echo $form->textField($users,'email',array('maxlength'=>32,'class'=>'span-7')); ?>
				<?php echo $form->error($users,'email'); ?>
				<?php /*<div class="small-px silent"></div>*/?>
			</div>
		</div>

		<?php if($setting->signup_photo == 1) {?>
		<div class="clearfix">
			<?php echo $form->labelEx($users,'photos'); ?>
			<div class="desc">
				<?php echo $form->textArea($users,'photos',array('rows'=>6, 'cols'=>50, 'class'=>'span-10 smaller')); ?>
				<?php echo $form->error($users,'photos'); ?>
				<div class="small-px silent"><?php echo Yii::t('phrase', 'Inputkan alamat url photo Anda.<br/>contoh: http://ommu.co/putrasudaryanto.jpg');?></div>
			</div>
		</div>
		<?php }?>
		
		<?php if(($users->isNewRecord && $setting->signup_random == 0) || !$users->isNewRecord) {?>
		<div class="clearfix">
			<label><?php echo $users->getAttributeLabel('newPassword')?> <?php echo $users->isNewRecord ? '<span class="required">*</span>' : '';?></label>
			<div class="desc">
				<?php echo $form->passwordField($users,'newPassword',array('maxlength'=>32,'class'=>'span-7')); ?>
				<?php echo $form->error($users,'newPassword'); ?>
			</div>
		</div>

		<div class="clearfix">
			<label><?php echo $users->getAttributeLabel('confirmPassword')?> <?php echo $users->isNewRecord ? '<span class="required">*</span>' : '';?></label>
			<div class="desc">
				<?php echo $form->passwordField($users,'confirmPassword',array('maxlength'=>32,'class'=>'span-7')); ?>
				<?php echo $form->error($users,'confirmPassword'); ?>
			</div>
		</div>
		<?php }?>

		<?php if(($users->isNewRecord && $setting->signup_approve == 0) || !$users->isNewRecord) {?>
		<div class="clearfix publish">
			<?php echo $form->labelEx($users,'enabled'); ?>
			<div class="desc">
				<?php echo $form->checkBox($users,'enabled'); ?>
				<?php echo $form->labelEx($users,'enabled'); ?>
				<?php echo $form->error($users,'enabled'); ?>
				<?php /*<div class="small-px silent"></div>*/?>
			</div>
		</div>
		<?php }?>

		<?php if(($users->isNewRecord && $setting->signup_verifyemail == 1) || !$users->isNewRecord) {?>
		<div class="clearfix publish">
			<?php echo $form->labelEx($users,'verified'); ?>
			<div class="desc">
				<?php echo $form->checkBox($users,'verified'); ?>
				<?php echo $form->labelEx($users,'verified'); ?>
				<?php echo $form->error($users,'verified'); ?>
				<?php /*<div class="small-px silent"></div>*/?>
			</div>
		</div>
		<?php }?>

	</fieldset>
</div>
<div class="dialog-submit">
	<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('phrase', 'Create') : Yii::t('phrase', 'Save') ,array('onclick' => 'setEnableSave()')); ?>
	<?php echo CHtml::button(Yii::t('phrase', 'Cancel'), array('id'=>'closed')); ?>
</div>
<?php $this->endWidget(); ?>


