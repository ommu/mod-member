<?php
/**
 * Member Profile Categories (member-profile-category)
 * @var $this app\components\View
 * @var $this ommu\member\controllers\setting\profile\CategoryController
 * @var $model ommu\member\models\MemberProfileCategory
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2018 OMMU (www.ommu.id)
 * @created date 2 October 2018, 09:58 WIB
 * @modified date 2 September 2019, 18:27 WIB
 * @link https://github.com/ommu/mod-member
 *
 */

use yii\helpers\Html;
use app\components\widgets\ActiveForm;
use ommu\member\models\MemberProfileCategory;
use ommu\selectize\Selectize;
use yii\helpers\ArrayHelper;
?>

<div class="member-profile-category-form">

<?php $form = ActiveForm::begin([
	'options' => ['class' => 'form-horizontal form-label-left'],
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

<?php $parents = MemberProfileCategory::getCategory($model->profile_id, 1);
echo $form->field($model, 'parent_id')
	->widget(Selectize::className(), [
		'options' => [
			'placeholder' => Yii::t('app', 'Select a parent category..'),
		],
		'items' => ArrayHelper::merge(['' => Yii::t('app', 'Select a parent category..')], $parents),
		'pluginOptions' => [
			'valueField' => 'id',
			'labelField' => 'label',
			'searchField' => ['label'],
			'persist' => false,
			'createOnBlur' => false,
			'create' => true,
		],
	])
	->label($model->getAttributeLabel('parent_id')); ?>

<?php echo $form->field($model, 'cat_name_i')
	->textInput(['maxlength' => true])
	->label($model->getAttributeLabel('cat_name_i')); ?>

<?php echo $form->field($model, 'cat_desc_i')
	->textarea(['rows' => 4, 'cols' => 50, 'maxlength' => true])
	->label($model->getAttributeLabel('cat_desc_i')); ?>

<?php 
if ($model->isNewRecord && !$model->getErrors()) {
    $model->publish = 1;
}
echo $form->field($model, 'publish')
	->checkbox()
	->label($model->getAttributeLabel('publish')); ?>

<hr/>

<?php echo $form->field($model, 'submitButton')
	->submitButton(); ?>

<?php ActiveForm::end(); ?>

</div>