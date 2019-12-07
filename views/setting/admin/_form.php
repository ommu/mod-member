<?php
/**
 * Member Settings (member-setting)
 * @var $this app\components\View
 * @var $this ommu\member\controllers\setting\AdminController
 * @var $model ommu\member\models\MemberSetting
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 5 November 2018, 06:17 WIB
 * @modified date 3 September 2019, 10:42 WIB
 * @link https://github.com/ommu/mod-member
 *
 */

use yii\helpers\Html;
use app\components\widgets\ActiveForm;
use ommu\member\models\MemberProfile;
use ommu\selectize\Selectize;
use yii\helpers\ArrayHelper;
?>

<div class="member-setting-form">

<?php $form = ActiveForm::begin([
	'options' => ['class'=>'form-horizontal form-label-left'],
	'enableClientValidation' => false,
	'enableAjaxValidation' => false,
	//'enableClientScript' => true,
	'fieldConfig' => [
		'errorOptions' => [
			'encode' => false,
		],
	],
]); ?>

<?php //echo $form->errorSummary($model);?>

<?php if($model->isNewRecord && !$model->getErrors())
	$model->license = $model->licenseCode();
echo $form->field($model, 'license')
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('license'))
	->hint(Yii::t('app', 'Enter the your license key that is provided to you when you purchased this plugin. If you do not know your license key, please contact support team.').'<br/>'.Yii::t('app', 'Format: XXXX-XXXX-XXXX-XXXX')); ?>

<?php $permission = $model::getPermission();
echo $form->field($model, 'permission', ['template' => '{label}{beginWrapper}{hint}{input}{error}{endWrapper}'])
	->radioList($permission)
	->label($model->getAttributeLabel('permission'))
	->hint(Yii::t('app', 'Select whether or not you want to let the public (visitors that are not logged-in) to view the following sections of your social network. In some cases (such as Profiles, Blogs, and Albums), if you have given them the option, your users will be able to make their pages private even though you have made them publically viewable here. For more permissions settings, please visit the General Settings page.')); ?>

<?php echo $form->field($model, 'meta_description')
	->textarea(['rows'=>6, 'cols'=>50])
	->label($model->getAttributeLabel('meta_description')); ?>

<?php echo $form->field($model, 'meta_keyword')
	->textarea(['rows'=>6, 'cols'=>50])
	->label($model->getAttributeLabel('meta_keyword')); ?>

<hr/>

<?php $profiles = MemberProfile::getProfile(1);
echo $form->field($model, 'personal_profile_id')
	->widget(Selectize::className(), [
		'options' => [
			'placeholder' => Yii::t('app', 'Select a personal profile..'),
		],
		'items' => ArrayHelper::merge([''=>Yii::t('app', 'Select a personal profile..')], $profiles),
		'pluginOptions' => [
			'valueField' => 'id',
			'labelField' => 'label',
			'searchField' => ['label'],
			'persist' => false,
			'createOnBlur' => false,
			'create' => true,
		],
	])
	->label($model->getAttributeLabel('personal_profile_id')); ?>

<?php echo $form->field($model, 'company_profile_id')
	->widget(Selectize::className(), [
		'options' => [
			'placeholder' => Yii::t('app', 'Select a company profile..'),
		],
		'items' => ArrayHelper::merge([''=>Yii::t('app', 'Select a company profile..')], $profiles),
		'pluginOptions' => [
			'valueField' => 'id',
			'labelField' => 'label',
			'searchField' => ['label'],
			'persist' => false,
			'createOnBlur' => false,
			'create' => true,
		],
	])
	->label($model->getAttributeLabel('company_profile_id')); ?>

<?php echo $form->field($model, 'group_profile_id')
	->widget(Selectize::className(), [
		'options' => [
			'placeholder' => Yii::t('app', 'Select a group profile..'),
		],
		'items' => ArrayHelper::merge([''=>Yii::t('app', 'Select a group profile..')], $profiles),
		'pluginOptions' => [
			'valueField' => 'id',
			'labelField' => 'label',
			'searchField' => ['label'],
			'persist' => false,
			'createOnBlur' => false,
			'create' => true,
		],
	])
	->label($model->getAttributeLabel('group_profile_id')); ?>

<hr/>

<?php echo $form->field($model, 'photo_limit')
	->textInput(['type'=>'number'])
	->label($model->getAttributeLabel('photo_limit')); ?>

<?php $photoResize = $model::getPhotoResize();
echo $form->field($model, 'photo_resize')
	->radioList($photoResize)
	->label($model->getAttributeLabel('photo_resize')); ?>

<?php $photoResizeSizeHeight = $form->field($model, 'photo_resize_size[height]', ['template' => '{beginWrapper}{input}{endWrapper}', 'horizontalCssClasses' => ['wrapper'=>'col-sm-5 col-xs-6'], 'options' => ['tag' => null]])
	->textInput(['type'=>'number', 'min'=>0, 'maxlength'=>'4', 'placeholder'=>$model->getAttributeLabel('height')])
	->label($model->getAttributeLabel('photo_resize_size[height]')); ?>

<?php echo $form->field($model, 'photo_resize_size[width]', ['template' => '{hint}{beginWrapper}{input}{endWrapper}'.$photoResizeSizeHeight.'{error}', 'horizontalCssClasses' => ['wrapper'=>'col-sm-4 col-xs-6 col-sm-offset-3', 'error'=>'col-sm-9 col-xs-12 col-sm-offset-3', 'hint'=>'col-sm-9 col-xs-12 col-sm-offset-3']])
	->textInput(['type'=>'number', 'min'=>0, 'maxlength'=>'4', 'placeholder'=>$model->getAttributeLabel('width')])
	->label($model->getAttributeLabel('photo_resize_size'))
	->hint(Yii::t('app', 'If you have selected "Yes" above, please input the maximum dimensions for the project image. If your users upload a image that is larger than these dimensions, the server will attempt to scale them down automatically. This feature requires that your PHP server is compiled with support for the GD Libraries.')); ?>

<?php $photoViewSizeSmallHeight = $form->field($model, 'photo_view_size[small][height]', ['template' => '{beginWrapper}{input}{endWrapper}', 'horizontalCssClasses' => ['wrapper'=>'col-sm-5 col-xs-6'], 'options' => ['tag' => null]])
	->textInput(['type'=>'number', 'min'=>0, 'maxlength'=>'4', 'placeholder'=>$model->getAttributeLabel('height')])
	->label($model->getAttributeLabel('photo_view_size[small][height]')); ?>

<?php echo $form->field($model, 'photo_view_size[small][width]', ['template' => '{label}<div class="h5 col-sm-9 col-xs-12">'.$model->getAttributeLabel('photo_view_size[small]').'</div>{beginWrapper}{input}{endWrapper}'.$photoViewSizeSmallHeight.'{error}', 'horizontalCssClasses' => ['wrapper'=>'col-sm-4 col-xs-6 col-sm-offset-3', 'error'=>'col-sm-9 col-xs-12 col-sm-offset-3']])
	->textInput(['type'=>'number', 'min'=>0, 'maxlength'=>'4', 'placeholder'=>$model->getAttributeLabel('width')])
	->label($model->getAttributeLabel('photo_view_size')); ?>

<?php $photoViewSizeMediumHeight = $form->field($model, 'photo_view_size[medium][height]', ['template' => '{beginWrapper}{input}{endWrapper}', 'horizontalCssClasses' => ['wrapper'=>'col-sm-5 col-xs-6'], 'options' => ['tag' => null]])
	->textInput(['type'=>'number', 'min'=>0, 'maxlength'=>'4', 'placeholder'=>$model->getAttributeLabel('height')])
	->label($model->getAttributeLabel('photo_view_size[medium][height]')); ?>

<?php echo $form->field($model, 'photo_view_size[medium][width]', ['template' => '<div class="h5 col-sm-9 col-xs-12 col-sm-offset-3 mt-0">'.$model->getAttributeLabel('photo_view_size[medium]').'</div>{beginWrapper}{input}{endWrapper}'.$photoViewSizeMediumHeight.'{error}', 'horizontalCssClasses' => ['wrapper'=>'col-sm-4 col-xs-6 col-sm-offset-3', 'error'=>'col-sm-9 col-xs-12 col-sm-offset-3']])
	->textInput(['type'=>'number', 'min'=>0, 'maxlength'=>'4', 'placeholder'=>$model->getAttributeLabel('width')])
	->label($model->getAttributeLabel('photo_view_size[medium][width]')); ?>

<?php $photoViewSizeLargeHeight = $form->field($model, 'photo_view_size[large][height]', ['template' => '{beginWrapper}{input}{endWrapper}', 'horizontalCssClasses' => ['wrapper'=>'col-sm-5 col-xs-6'], 'options' => ['tag' => null]])
	->textInput(['type'=>'number', 'min'=>0, 'maxlength'=>'4', 'placeholder'=>$model->getAttributeLabel('height')])
	->label($model->getAttributeLabel('photo_view_size[large][height]')); ?>

<?php echo $form->field($model, 'photo_view_size[large][width]', ['template' => '<div class="h5 col-sm-9 col-xs-12 col-sm-offset-3 mt-0">'.$model->getAttributeLabel('photo_view_size[large]').'</div>{beginWrapper}{input}{endWrapper}'.$photoViewSizeLargeHeight.'{error}', 'horizontalCssClasses' => ['wrapper'=>'col-sm-4 col-xs-6 col-sm-offset-3', 'error'=>'col-sm-9 col-xs-12 col-sm-offset-3']])
	->textInput(['type'=>'number', 'min'=>0, 'maxlength'=>'4', 'placeholder'=>$model->getAttributeLabel('width')])
	->label($model->getAttributeLabel('photo_view_size[large][width]')); ?>

<?php $photoHeaderViewSize = $form->field($model, 'photo_header_view_size[height]', ['template' => '{beginWrapper}{input}{endWrapper}', 'horizontalCssClasses' => ['wrapper'=>'col-sm-5 col-xs-6'], 'options' => ['tag' => null]])
	->textInput(['type'=>'number', 'min'=>0, 'maxlength'=>'4', 'placeholder'=>$model->getAttributeLabel('height')])
	->label($model->getAttributeLabel('photo_header_view_size[height]')); ?>

<?php echo $form->field($model, 'photo_header_view_size[width]', ['template' => '{label}{beginWrapper}{input}{endWrapper}'.$photoHeaderViewSize.'{error}{hint}', 'horizontalCssClasses' => ['wrapper'=>'col-sm-4 col-xs-6', 'error'=>'col-sm-9 col-xs-12 col-sm-offset-3', 'hint'=>'col-sm-9 col-xs-12 col-sm-offset-3']])
	->textInput(['type'=>'number', 'min'=>0, 'maxlength'=>'4', 'placeholder'=>$model->getAttributeLabel('width')])
	->label($model->getAttributeLabel('photo_header_view_size')); ?>

<?php echo $form->field($model, 'photo_file_type')
	->textInput()
	->label($model->getAttributeLabel('photo_file_type'))
	->hint(Yii::t('app', 'pisahkan jenis file dengan koma (,). example: "jpg, jpeg, bmp, gif, png"')); ?>

<hr/>

<?php echo $form->field($model, 'profile_user_limit')
	->textInput(['type'=>'number', 'min'=>'1'])
	->label($model->getAttributeLabel('profile_user_limit')); ?>

<?php echo $form->field($model, 'profile_page_user_auto_follow')
	->checkbox()
	->label($model->getAttributeLabel('profile_page_user_auto_follow')); ?>

<?php echo $form->field($model, 'friends_auto_follow')
	->checkbox()
	->label($model->getAttributeLabel('friends_auto_follow')); ?>

<hr/>

<?php echo $form->field($model, 'submitButton')
	->submitButton(); ?>

<?php ActiveForm::end(); ?>

</div>