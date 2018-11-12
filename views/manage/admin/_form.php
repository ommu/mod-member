<?php
/**
 * Members (members)
 * @var $this yii\web\View
 * @var $this ommu\member\controllers\manage\AdminController
 * @var $model ommu\member\models\Members
 * @var $form yii\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 30 October 2018, 22:51 WIB
 * @link https://github.com/ommu/mod-member
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use ommu\member\models\Members;
use ommu\member\models\MemberProfile;
?>

<div class="members-form">

<?php $form = ActiveForm::begin([
	'options' => [
		'class' => 'form-horizontal form-label-left',
		'enctype' => 'multipart/form-data',
	],
	'enableClientValidation' => false,
	'enableAjaxValidation' => false,
	//'enableClientScript' => true,
]); ?>

<?php //echo $form->errorSummary($model);?>

<?php $profile = MemberProfile::getProfile();
echo $form->field($model, 'profile_id', ['template' => '{label}<div class="col-md-6 col-sm-9 col-xs-12">{input}{error}</div>'])
	->dropDownList($profile, ['prompt'=>''])
	->label($model->getAttributeLabel('profile_id'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php echo $form->field($model, 'username', ['template' => '{label}<div class="col-md-6 col-sm-9 col-xs-12">{input}{error}</div>'])
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('username'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php echo $form->field($model, 'displayname', ['template' => '{label}<div class="col-md-6 col-sm-9 col-xs-12">{input}{error}</div>'])
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('displayname'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<div class="form-group field-photo_header">
	<?php echo $form->field($model, 'photo_header', ['template' => '{label}', 'options' => ['tag' => null]])
		->label($model->getAttributeLabel('photo_header'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>
	<div class="col-md-6 col-sm-9 col-xs-12">
		<?php echo !$model->isNewRecord && $model->old_photo_header_i != '' ? Html::img(join('/', [Url::Base(), Members::getUploadPath(false), $model->member_id, $model->old_photo_header_i]), ['class'=>'mb-15', 'width'=>'100%']) : '';?>
		<?php echo $form->field($model, 'photo_header', ['template' => '{input}{error}'])
			->fileInput()
			->label($model->getAttributeLabel('photo_header'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>
	</div>
</div>

<div class="form-group field-photo_profile">
	<?php echo $form->field($model, 'photo_profile', ['template' => '{label}', 'options' => ['tag' => null]])
		->label($model->getAttributeLabel('photo_profile'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>
	<div class="col-md-6 col-sm-9 col-xs-12">
		<?php echo !$model->isNewRecord && $model->old_photo_profile_i != '' ? Html::img(join('/', [Url::Base(), Members::getUploadPath(false), $model->member_id, $model->old_photo_profile_i]), ['class'=>'mb-15', 'width'=>'100%']) : '';?>
		<?php echo $form->field($model, 'photo_profile', ['template' => '{input}{error}'])
			->fileInput()
			->label($model->getAttributeLabel('photo_profile'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>
	</div>
</div>

<?php echo $form->field($model, 'short_biography', ['template' => '{label}<div class="col-md-6 col-sm-9 col-xs-12">{input}{error}</div>'])
	->textarea(['rows'=>2, 'rows'=>6])
	->label($model->getAttributeLabel('short_biography'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php $memberPrivate = Members::getMemberPrivate();
if($model->isNewRecord && !$model->getErrors())
	$model->member_private = 0;
echo $form->field($model, 'member_private', ['template' => '{label}<div class="col-md-6 col-sm-9 col-xs-12">{input}{error}</div>'])
	->dropDownList($memberPrivate, ['prompt'=>''])
	->label($model->getAttributeLabel('member_private'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php echo $form->field($model, 'approved', ['template' => '{label}<div class="col-md-6 col-sm-9 col-xs-12 checkbox">{input}{error}</div>'])
	->checkbox(['label'=>''])
	->label($model->getAttributeLabel('approved'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php echo $form->field($model, 'publish', ['template' => '{label}<div class="col-md-6 col-sm-9 col-xs-12 checkbox">{input}{error}</div>'])
	->checkbox(['label'=>''])
	->label($model->getAttributeLabel('publish'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<div class="ln_solid"></div>
<div class="form-group">
	<div class="col-md-6 col-sm-9 col-xs-12 col-sm-offset-3">
		<?php echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']); ?>
	</div>
</div>

<?php ActiveForm::end(); ?>

</div>