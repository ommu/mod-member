<?php
/**
 * Member Profiles (member-profile)
 * @var $this app\components\View
 * @var $this ommu\member\controllers\setting\profile\AdminController
 * @var $model ommu\member\models\MemberProfile
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 OMMU (www.ommu.id)
 * @created date 2 October 2018, 09:48 WIB
 * @modified date 2 September 2019, 18:27 WIB
 * @link https://github.com/ommu/mod-member
 *
 */

use yii\helpers\Html;
use app\components\widgets\ActiveForm;
use ommu\users\models\Assignments;

$js = <<<JS
	$('.field-profile_personal input[name="profile_personal"]').on('change', function() {
		if($(this).prop('checked')) {
			$('div#pages').hide();
		} else {
			$('div#pages').show();
		}
	});
	$('.field-multiple_user input[name="multiple_user"]').on('change', function() {
		if($(this).prop('checked')) {
			$('div.field-user_limit').show();
		} else {
			$('div.field-user_limit').hide();
		}
	});
JS;
	$this->registerJs($js, $this::POS_READY);
?>

<div class="member-profile-form">

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

<?php echo $form->field($model, 'profile_name_i')
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('profile_name_i')); ?>

<?php echo $form->field($model, 'profile_desc_i')
	->textarea(['rows'=>6, 'cols'=>50, 'maxlength'=>true])
	->label($model->getAttributeLabel('profile_desc_i')); ?>

<?php $assignments = Assignments::getRoles();
echo $form->field($model, 'assignment_roles')
	->checkboxList($assignments)
	->label($model->getAttributeLabel('assignment_roles')); ?>

<?php if($model->isNewRecord && !$model->getErrors()) {
	$model->profile_personal = 1;
	$model->multiple_user = 0;
}
echo $form->field($model, 'profile_personal')
	->checkbox()
	->label($model->getAttributeLabel('profile_personal')); ?>

<div id="pages" class="mb-3" <?php echo $model->profile_personal == 1 ? 'style="display: none;"' : ''; ?>>
<?php echo $form->field($model, 'multiple_user')
	->checkbox()
	->label($model->getAttributeLabel('multiple_user')); ?>

<?php echo $form->field($model, 'user_limit', ['options' => ['style' => $model->multiple_user == 0 ? 'display: none' : '']])
	->textInput(['type'=>'number', 'min'=>'1'])
	->label($model->getAttributeLabel('user_limit'))
	->hint(Yii::t('app', 'User limit recommendation is {limit}', ['limit'=>$model->multipleUserLimit])); ?>
</div>

<?php if($model->isNewRecord && !$model->getErrors())
	$model->publish = 1;
echo $form->field($model, 'publish')
	->checkbox()
	->label($model->getAttributeLabel('publish')); ?>

<hr/>

<?php echo $form->field($model, 'submitButton')
	->submitButton(); ?>

<?php ActiveForm::end(); ?>

</div>