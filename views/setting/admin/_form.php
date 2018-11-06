<?php
/**
 * Member Settings (member-setting)
 * @var $this yii\web\View
 * @var $this ommu\member\controllers\setting\AdminController
 * @var $model ommu\member\models\MemberSetting
 * @var $form yii\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 5 November 2018, 06:17 WIB
 * @modified date 5 November 2018, 06:17 WIB
 * @modified by Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @link https://github.com/ommu/mod-member
 *
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use ommu\member\models\MemberSetting;
use ommu\member\models\MemberProfile;
?>

<div class="member-setting-form">

<?php $form = ActiveForm::begin([
	'options' => [
		'class' => 'form-horizontal form-label-left',
		//'enctype' => 'multipart/form-data',
	],
	'enableClientValidation' => true,
	'enableAjaxValidation' => false,
	//'enableClientScript' => true,
]); ?>

<?php //echo $form->errorSummary($model);?>

<?php if($model->isNewRecord && !$model->getErrors())
	$model->license = $this->licenseCode();
echo $form->field($model, 'license', ['template' => '{label}<div class="col-md-6 col-sm-9 col-xs-12"><span class="small-px mb-10">'.Yii::t('app', 'Enter the your license key that is provided to you when you purchased this plugin. If you do not know your license key, please contact support team.').'</span>{input}{error}<span class="small-px">'.Yii::t('app', 'Format: XXXX-XXXX-XXXX-XXXX').'</span></div>'])
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('license'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php $permission = MemberSetting::getPermission();
echo $form->field($model, 'permission', ['template' => '{label}<div class="col-md-6 col-sm-9 col-xs-12"><span class="small-px mb-10">'.Yii::t('app', 'Select whether or not you want to let the public (visitors that are not logged-in) to view the following sections of your social network. In some cases (such as Profiles, Blogs, and Albums), if you have given them the option, your users will be able to make their pages private even though you have made them publically viewable here. For more permissions settings, please visit the General Settings page.').'</span>{input}{error}</div>'])
	->radioList($permission, ['class'=>'desc mt-10', 'separator' => '<br />'])
	->label($model->getAttributeLabel('permission'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php echo $form->field($model, 'meta_description', ['template' => '{label}<div class="col-md-6 col-sm-9 col-xs-12">{input}{error}</div>'])
	->textarea(['rows'=>2, 'rows'=>6])
	->label($model->getAttributeLabel('meta_description'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php echo $form->field($model, 'meta_keyword', ['template' => '{label}<div class="col-md-6 col-sm-9 col-xs-12">{input}{error}</div>'])
	->textarea(['rows'=>2, 'rows'=>6])
	->label($model->getAttributeLabel('meta_keyword'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php echo $form->field($model, 'form_custom_insert_field', ['template' => '{label}<div class="col-md-6 col-sm-9 col-xs-12">{input}{error}</div>'])
	->textarea(['rows'=>2, 'rows'=>6])
	->label($model->getAttributeLabel('form_custom_insert_field'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php echo $form->field($model, 'profile_user_limit', ['template' => '{label}<div class="col-md-6 col-sm-9 col-xs-12">{input}{error}</div>'])
	->textInput(['type'=>'number', 'min'=>'1'])
	->label($model->getAttributeLabel('profile_user_limit'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php $profileViews = MemberProfile::getProfile(1);
echo $form->field($model, 'profile_views', ['template' => '{label}<div class="col-md-6 col-sm-9 col-xs-12">{input}{error}</div>'])
	->checkboxList($profileViews, ['class'=>'desc', 'separator' => '<br />'])
	->label($model->getAttributeLabel('profile_views'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php echo $form->field($model, 'photo_limit', ['template' => '{label}<div class="col-md-6 col-sm-9 col-xs-12">{input}{error}</div>'])
	->textInput(['type'=>'number', 'min'=>'1'])
	->label($model->getAttributeLabel('photo_limit'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php echo $form->field($model, 'photo_resize', ['template' => '{label}<div class="col-md-6 col-sm-9 col-xs-12 checkbox">{input}{error}</div>'])
	->checkbox(['label'=>''])
	->label($model->getAttributeLabel('photo_resize'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<div class="form-group field-photo_resize_size">
	<?php echo $form->field($model, 'photo_resize_size[i]', ['template' => '{label}', 'options' => ['tag' => null]])
		->label($model->getAttributeLabel('photo_resize_size[i]'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>
	<?php echo $form->field($model, 'photo_resize_size[width]', ['template' => '{input}{error}', 'options' => ['class' => 'col-md-3 col-sm-4 col-xs-6']])
		->textInput(['type'=>'number', 'min'=>0, 'maxlength'=>'3', 'placeholder'=>$model->getAttributeLabel('photo_resize_size[width]')])
		->label($model->getAttributeLabel('photo_resize_size[width]'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>
	<?php echo $form->field($model, 'photo_resize_size[height]', ['template' => '{input}{error}', 'options' => ['class' => 'col-md-3 col-sm-5 col-xs-6']])
		->textInput(['type'=>'number', 'min'=>0, 'maxlength'=>'3', 'placeholder'=>$model->getAttributeLabel('photo_resize_size[height]')])
		->label($model->getAttributeLabel('photo_resize_size[height]'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>
</div>

<div class="form-group field-photo_view_size">
	<?php echo $form->field($model, 'photo_view_size[i]', ['template' => '{label}', 'options' => ['tag' => null]])
		->label($model->getAttributeLabel('photo_view_size[i]'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>
	<?php echo $form->field($model, 'photo_view_size[width]', ['template' => '{input}{error}', 'options' => ['class' => 'col-md-3 col-sm-4 col-xs-6']])
		->textInput(['type'=>'number', 'min'=>0, 'maxlength'=>'3', 'placeholder'=>$model->getAttributeLabel('photo_view_size[width]')])
		->label($model->getAttributeLabel('photo_view_size[width]'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>
	<?php echo $form->field($model, 'photo_view_size[height]', ['template' => '{input}{error}', 'options' => ['class' => 'col-md-3 col-sm-5 col-xs-6']])
		->textInput(['type'=>'number', 'min'=>0, 'maxlength'=>'3', 'placeholder'=>$model->getAttributeLabel('photo_view_size[height]')])
		->label($model->getAttributeLabel('photo_view_size[height]'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>
</div>

<div class="form-group field-photo_header_view_size">
	<?php echo $form->field($model, 'photo_header_view_size[i]', ['template' => '{label}', 'options' => ['tag' => null]])
		->label($model->getAttributeLabel('photo_header_view_size[i]'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>
	<?php echo $form->field($model, 'photo_header_view_size[width]', ['template' => '{input}{error}', 'options' => ['class' => 'col-md-3 col-sm-4 col-xs-6']])
		->textInput(['type'=>'number', 'min'=>0, 'maxlength'=>'3', 'placeholder'=>$model->getAttributeLabel('photo_header_view_size[width]')])
		->label($model->getAttributeLabel('photo_header_view_size[width]'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>
	<?php echo $form->field($model, 'photo_header_view_size[height]', ['template' => '{input}{error}', 'options' => ['class' => 'col-md-3 col-sm-5 col-xs-6']])
		->textInput(['type'=>'number', 'min'=>0, 'maxlength'=>'3', 'placeholder'=>$model->getAttributeLabel('photo_header_view_size[height]')])
		->label($model->getAttributeLabel('photo_header_view_size[height]'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>
</div>

<?php echo $form->field($model, 'photo_file_type', ['template' => '{label}<div class="col-md-6 col-sm-9 col-xs-12">{input}{error}<span class="small-px">'.Yii::t('app', 'pisahkan jenis file dengan koma (,). example: "jpg, jpeg, png, bmp, gif"').'</span></div>'])
	->textInput()
	->label($model->getAttributeLabel('photo_file_type'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php echo $form->field($model, 'profile_page_user_auto_follow', ['template' => '{label}<div class="col-md-6 col-sm-9 col-xs-12 checkbox">{input}{error}</div>'])
	->checkbox(['label'=>''])
	->label($model->getAttributeLabel('profile_page_user_auto_follow'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php echo $form->field($model, 'friends_auto_follow', ['template' => '{label}<div class="col-md-6 col-sm-9 col-xs-12 checkbox">{input}{error}</div>'])
	->checkbox(['label'=>''])
	->label($model->getAttributeLabel('friends_auto_follow'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<div class="ln_solid"></div>
<div class="form-group">
	<div class="col-md-6 col-sm-9 col-xs-12 col-sm-offset-3">
		<?php echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']); ?>
	</div>
</div>

<?php ActiveForm::end(); ?>

</div>