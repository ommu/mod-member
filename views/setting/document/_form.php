<?php
/**
 * Member Document Types (member-document-type)
 * @var $this app\components\View
 * @var $this ommu\member\controllers\setting\DocumentController
 * @var $model ommu\member\models\MemberDocumentType
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2018 OMMU (www.ommu.id)
 * @created date 2 October 2018, 11:07 WIB
 * @modified date 27 October 2018, 22:44 WIB
 * @link https://github.com/ommu/mod-member
 *
 */

use yii\helpers\Html;
use app\components\widgets\ActiveForm;
?>

<div class="member-document-type-form">

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

<?php echo $form->field($model, 'document_name_i')
	->textInput(['maxlength' => true])
	->label($model->getAttributeLabel('document_name_i')); ?>

<?php echo $form->field($model, 'document_desc_i')
	->textarea(['rows' => 6, 'cols' => 50, 'maxlength' => true])
	->label($model->getAttributeLabel('document_desc_i')); ?>

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