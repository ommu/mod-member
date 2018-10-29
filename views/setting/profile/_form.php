<?php
/**
 * Member Profiles (member-profile)
 * @var $this yii\web\View
 * @var $this ommu\member\controllers\setting\ProfileController
 * @var $model ommu\member\models\MemberProfile
 * @var $form yii\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 2 October 2018, 09:48 WIB
 * @link https://github.com/ommu/mod-member
 *
 */

use Yii;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$js = <<<JS
	$('.field-profile_personal input[name="profile_personal"]').on('change', function() {
		if($(this).prop('checked')) {
			$('div#not-personal').hide();
		} else {
			$('div#not-personal').show();
		}
	});
	$('.field-multiple_user input[name="multiple_user"]').on('change', function() {
		if($(this).prop('checked')) {
			$('div#user-limit').show();
		} else {
			$('div#user-limit').hide();
		}
	});
JS;
	$this->registerJs($js, \yii\web\View::POS_READY);
?>

<div class="member-profile-form">

<?php $form = ActiveForm::begin([
	'options' => [
		'class' => 'form-horizontal form-label-left',
		//'enctype' => 'multipart/form-data',
	],
	'enableClientValidation' => false,
	'enableAjaxValidation' => false,
	//'enableClientScript' => true,
]); ?>

<?php //echo $form->errorSummary($model);?>

<?php echo $form->field($model, 'profile_name_i', ['template' => '{label}<div class="col-md-6 col-sm-9 col-xs-12">{input}{error}</div>'])
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('profile_name_i'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php echo $form->field($model, 'profile_desc_i', ['template' => '{label}<div class="col-md-6 col-sm-9 col-xs-12">{input}{error}</div>'])
	->textarea(['rows'=>2, 'rows'=>6, 'maxlength'=>true])
	->label($model->getAttributeLabel('profile_desc_i'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php if($model->isNewRecord && !$model->getErrors())
	$model->profile_personal = 1;
echo $form->field($model, 'profile_personal', ['template' => '{label}<div class="col-md-6 col-sm-9 col-xs-12 checkbox">{input}{error}</div>'])
	->checkbox(['label'=>''])
	->label($model->getAttributeLabel('profile_personal'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<div id="not-personal" <?php echo $model->profile_personal == 1 ? 'style="display: none;"' : ''; ?>>
	<?php if($model->isNewRecord && !$model->getErrors())
		$model->multiple_user = 0;
	echo $form->field($model, 'multiple_user', ['template' => '{label}<div class="col-md-6 col-sm-9 col-xs-12 checkbox">{input}{error}</div>'])
		->checkbox(['label'=>''])
		->label($model->getAttributeLabel('multiple_user'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

	<div id="user-limit" <?php echo $model->multiple_user == 0 ? 'style="display: none;"' : ''; ?>>
		<?php echo $form->field($model, 'user_limit', ['template' => '{label}<div class="col-md-6 col-sm-9 col-xs-12">{input}{error}</div>'])
			->textInput(['type'=>'number', 'min'=>'1'])
			->label($model->getAttributeLabel('user_limit'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>
	</div>
</div>

<?php echo $form->field($model, 'publish', ['template' => '{label}<div class="col-md-6 col-sm-9 col-xs-12 checkbox">{input}{error}</div>'])
	->checkbox(['label'=>''])
	->label($model->getAttributeLabel('publish'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<div class="ln_solid"></div>
<div class="form-group">
	<div class="col-md-6 col-sm-9 col-xs-12 col-md-offset-3">
		<?php echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']); ?>
	</div>
</div>

<?php ActiveForm::end(); ?>

</div>
