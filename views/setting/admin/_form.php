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
use app\components\ActiveForm;
use ommu\member\models\MemberSetting;
use ommu\member\models\MemberProfile;
?>

<div class="member-setting-form">

<?php $form = ActiveForm::begin([
	'options' => ['class'=>'form-horizontal form-label-left'],
	'enableClientValidation' => true,
	'enableAjaxValidation' => false,
	//'enableClientScript' => true,
]); ?>

<?php //echo $form->errorSummary($model);?>

<?php if($model->isNewRecord && !$model->getErrors())
	$model->license = $this->licenseCode();
echo $form->field($model, 'license')
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('license'))
	->hint(Yii::t('app', 'Enter the your license key that is provided to you when you purchased this plugin. If you do not know your license key, please contact support team.').'<br/>'.Yii::t('app', 'Format: XXXX-XXXX-XXXX-XXXX')); ?>

<?php $permission = MemberSetting::getPermission();
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

<?php echo $form->field($model, 'form_custom_insert_field')
	->textarea(['rows'=>6, 'cols'=>50])
	->label($model->getAttributeLabel('form_custom_insert_field')); ?>

<?php echo $form->field($model, 'profile_user_limit')
	->textInput(['type'=>'number', 'min'=>'1'])
	->label($model->getAttributeLabel('profile_user_limit')); ?>

<?php $profileViews = MemberProfile::getProfile(1);
echo $form->field($model, 'profile_views')
	->checkboxList($profileViews)
	->label($model->getAttributeLabel('profile_views')); ?>

<?php echo $form->field($model, 'photo_limit')
	->textInput(['type'=>'number', 'min'=>'1'])
	->label($model->getAttributeLabel('photo_limit')); ?>

<?php echo $form->field($model, 'photo_resize')
	->checkbox()
	->label($model->getAttributeLabel('photo_resize')); ?>

<?php $photo_resize_size_height = $form->field($model, 'photo_resize_size[height]', ['template' => '{beginWrapper}{input}{endWrapper}', 'horizontalCssClasses' => ['wrapper'=>'col-md-3 col-sm-5 col-xs-6 col-6'], 'options' => ['tag' => null]])
	->textInput(['type'=>'number', 'min'=>0, 'maxlength'=>'3', 'placeholder'=>$model->getAttributeLabel('photo_resize_size[height]')])
	->label($model->getAttributeLabel('photo_resize_size[height]')); ?>

<?php echo $form->field($model, 'photo_resize_size[width]', ['template' => '{label}{beginWrapper}{input}{endWrapper}'.$photo_resize_size_height.'{error}{hint}', 'horizontalCssClasses' => ['wrapper'=>'col-md-3 col-sm-4 col-xs-6 col-6', 'error'=>'col-md-6 col-sm-9 col-xs-12 col-12 col-sm-offset-3', 'hint'=>'col-md-6 col-sm-9 col-xs-12 col-12 col-sm-offset-3']])
	->textInput(['type'=>'number', 'min'=>0, 'maxlength'=>'3', 'placeholder'=>$model->getAttributeLabel('photo_resize_size[width]')])
	->label($model->getAttributeLabel('photo_resize_size')); ?>

<?php $photo_view_size_height = $form->field($model, 'photo_view_size[height]', ['template' => '{beginWrapper}{input}{endWrapper}', 'horizontalCssClasses' => ['wrapper'=>'col-md-3 col-sm-5 col-xs-6 col-6'], 'options' => ['tag' => null]])
	->textInput(['type'=>'number', 'min'=>0, 'maxlength'=>'3', 'placeholder'=>$model->getAttributeLabel('photo_view_size[height]')])
	->label($model->getAttributeLabel('photo_view_size[height]')); ?>

<?php echo $form->field($model, 'photo_view_size[width]', ['template' => '{label}{beginWrapper}{input}{endWrapper}'.$photo_view_size_height.'{error}{hint}', 'horizontalCssClasses' => ['wrapper'=>'col-md-3 col-sm-4 col-xs-6 col-6', 'error'=>'col-md-6 col-sm-9 col-xs-12 col-12 col-sm-offset-3', 'hint'=>'col-md-6 col-sm-9 col-xs-12 col-12 col-sm-offset-3']])
	->textInput(['type'=>'number', 'min'=>0, 'maxlength'=>'3', 'placeholder'=>$model->getAttributeLabel('photo_view_size[width]')])
	->label($model->getAttributeLabel('photo_view_size')); ?>

<?php $photo_header_view_size_height = $form->field($model, 'photo_header_view_size[height]', ['template' => '{beginWrapper}{input}{endWrapper}', 'horizontalCssClasses' => ['wrapper'=>'col-md-3 col-sm-5 col-xs-6 col-6'], 'options' => ['tag' => null]])
	->textInput(['type'=>'number', 'min'=>0, 'maxlength'=>'3', 'placeholder'=>$model->getAttributeLabel('photo_header_view_size[height]')])
	->label($model->getAttributeLabel('photo_header_view_size[height]')); ?>

<?php echo $form->field($model, 'photo_header_view_size[width]', ['template' => '{label}{beginWrapper}{input}{endWrapper}'.$photo_header_view_size_height.'{error}{hint}', 'horizontalCssClasses' => ['wrapper'=>'col-md-3 col-sm-4 col-xs-6 col-6', 'error'=>'col-md-6 col-sm-9 col-xs-12 col-12 col-sm-offset-3', 'hint'=>'col-md-6 col-sm-9 col-xs-12 col-12 col-sm-offset-3']])
	->textInput(['type'=>'number', 'min'=>0, 'maxlength'=>'3', 'placeholder'=>$model->getAttributeLabel('photo_header_view_size[width]')])
	->label($model->getAttributeLabel('photo_header_view_size')); ?>

<?php echo $form->field($model, 'photo_file_type')
	->textInput()
	->label($model->getAttributeLabel('photo_file_type'))
	->hint(Yii::t('app', 'pisahkan jenis file dengan koma (,). example: "jpg, jpeg, png, bmp, gif"')); ?>

<?php echo $form->field($model, 'profile_page_user_auto_follow')
	->checkbox()
	->label($model->getAttributeLabel('profile_page_user_auto_follow')); ?>

<?php echo $form->field($model, 'friends_auto_follow')
	->checkbox()
	->label($model->getAttributeLabel('friends_auto_follow')); ?>

<div class="ln_solid"></div>
<div class="form-group row">
	<div class="col-md-6 col-sm-9 col-xs-12 col-12 col-sm-offset-3">
		<?php echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']); ?>
	</div>
</div>

<?php ActiveForm::end(); ?>

</div>