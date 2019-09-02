<?php
/**
 * Member Profile Categories (member-profile-category)
 * @var $this app\components\View
 * @var $this ommu\member\controllers\setting\profile\CategoryController
 * @var $model ommu\member\models\MemberProfileCategory
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 2 October 2018, 09:58 WIB
 * @modified date 29 October 2018, 09:25 WIB
 * @link https://github.com/ommu/mod-member
 *
 */

use yii\helpers\Html;
use app\components\widgets\ActiveForm;
use ommu\member\models\MemberProfileCategory;
?>

<div class="member-profile-category-form">

<?php $form = ActiveForm::begin([
	'options' => ['class'=>'form-horizontal form-label-left'],
	'enableClientValidation' => true,
	'enableAjaxValidation' => false,
	//'enableClientScript' => true,
	'fieldConfig' => [
		'errorOptions' => [
			'encode' => false,
		],
	],
]); ?>

<?php //echo $form->errorSummary($model);?>

<?php $parent = MemberProfileCategory::getCategory($profile, 1);
echo $form->field($model, 'parent_id')
	->dropDownList($parent, ['prompt'=>''])
	->label($model->getAttributeLabel('parent_id')); ?>

<?php echo $form->field($model, 'cat_name_i')
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('cat_name_i')); ?>

<?php echo $form->field($model, 'cat_desc_i')
	->textarea(['rows'=>6, 'cols'=>50, 'maxlength'=>true])
	->label($model->getAttributeLabel('cat_desc_i')); ?>

<?php echo $form->field($model, 'publish')
	->checkbox()
	->label($model->getAttributeLabel('publish')); ?>

<div class="ln_solid"></div>

<?php echo $form->field($model, 'submitButton')
	->submitButton(); ?>

<?php ActiveForm::end(); ?>

</div>