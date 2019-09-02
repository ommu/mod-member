<?php
/**
 * Member Profile Documents (member-profile-document)
 * @var $this app\components\View
 * @var $this ommu\member\controllers\setting\profile\DocumentController
 * @var $model ommu\member\models\MemberProfileDocument
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 2 October 2018, 11:36 WIB
 * @modified date 30 October 2018, 11:08 WIB
 * @link https://github.com/ommu/mod-member
 *
 */

use yii\helpers\Html;
use app\components\widgets\ActiveForm;
use ommu\member\models\MemberDocumentType;
?>

<div class="member-profile-document-form">

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

<?php $document = MemberDocumentType::getType();
echo $form->field($model, 'document_id')
	->dropDownList($document, ['prompt'=>''])
	->label($model->getAttributeLabel('document_id')); ?>

<?php echo $form->field($model, 'required')
	->checkbox()
	->label($model->getAttributeLabel('required')); ?>

<?php echo $form->field($model, 'publish')
	->checkbox()
	->label($model->getAttributeLabel('publish')); ?>

<div class="ln_solid"></div>

<?php echo $form->field($model, 'submitButton')
	->submitButton(); ?>

<?php ActiveForm::end(); ?>

</div>