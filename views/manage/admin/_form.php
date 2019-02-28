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
use app\components\ActiveForm;
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
echo $form->field($model, 'profile_id')
	->dropDownList($profile, ['prompt'=>''])
	->label($model->getAttributeLabel('profile_id')); ?>

<?php echo $form->field($model, 'username')
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('username')); ?>

<?php echo $form->field($model, 'displayname')
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('displayname')); ?>

<?php $uploadPath = join('/', [Members::getUploadPath(false), $model->member_id]);
$photoHeader = !$model->isNewRecord && $model->old_photo_header_i != '' ? Html::img(join('/', [Url::Base(), $uploadPath, $model->old_photo_header_i]), ['class'=>'mb-15', 'width'=>'100%']) : '';
echo $form->field($model, 'photo_header', ['template' => '{label}{beginWrapper}<div>'.$photoHeader.'</div>{input}{error}{hint}{endWrapper}'])
	->fileInput()
	->label($model->getAttributeLabel('photo_header')); ?>

<?php $uploadPath = join('/', [Members::getUploadPath(false), $model->member_id]);
$photoProfile = !$model->isNewRecord && $model->old_photo_profile_i != '' ? Html::img(join('/', [Url::Base(), $uploadPath, $model->old_photo_profile_i]), ['class'=>'mb-15', 'width'=>'100%']) : '';
echo $form->field($model, 'photo_profile', ['template' => '{label}{beginWrapper}<div>'.$photoProfile.'</div>{input}{error}{hint}{endWrapper}'])
	->fileInput()
	->label($model->getAttributeLabel('photo_profile')); ?>

<?php echo $form->field($model, 'short_biography')
	->textarea(['rows'=>6, 'cols'=>50])
	->label($model->getAttributeLabel('short_biography')); ?>

<?php $memberPrivate = Members::getMemberPrivate();
if($model->isNewRecord && !$model->getErrors())
	$model->member_private = 0;
echo $form->field($model, 'member_private')
	->dropDownList($memberPrivate, ['prompt'=>''])
	->label($model->getAttributeLabel('member_private')); ?>

<?php echo $form->field($model, 'approved')
	->checkbox()
	->label($model->getAttributeLabel('approved')); ?>

<?php echo $form->field($model, 'publish')
	->checkbox()
	->label($model->getAttributeLabel('publish')); ?>

<div class="ln_solid"></div>
<div class="form-group row">
	<div class="col-md-6 col-sm-9 col-xs-12 offset-sm-3">
		<?php echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']); ?>
	</div>
</div>

<?php ActiveForm::end(); ?>

</div>