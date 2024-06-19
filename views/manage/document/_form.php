<?php
/**
 * Member Documents (member-documents)
 * @var $this app\components\View
 * @var $this ommu\member\controllers\manage\DocumentController
 * @var $model ommu\member\models\MemberDocuments
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2018 OMMU (www.ommu.id)
 * @created date 1 November 2018, 09:12 WIB
 * @link https://github.com/ommu/mod-member
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\widgets\ActiveForm;
use ommu\member\models\MemberDocuments;
?>

<div class="member-documents-form">

<?php $form = ActiveForm::begin([
	'options' => [
		'class' => 'form-horizontal form-label-left',
		'enctype' => 'multipart/form-data',
	],
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

<?php echo $form->field($model, 'profile_document_id')
	->dropDownList($document, ['prompt' => ''])
	->label($model->getAttributeLabel('profile_document_id')); ?>

<?php $documentFilename = !$model->isNewRecord && $model->old_document_filename_i != '' ? Html::img(Url::to(join('/', ['@webpublic', MemberDocuments::getUploadPath(false), $model->old_document_filename_i])), ['alt' => $model->old_document_filename_i, 'class' => 'd-block border border-width-3 mb-4']).$model->old_document_filename_i.'<hr/>' : '';
echo $form->field($model, 'document_filename', ['template' => '{label}{beginWrapper}<div>'.$documentFilename.'</div>{input}{error}{hint}{endWrapper}'])
	->fileInput()
	->label($model->getAttributeLabel('document_filename')); ?>

<?php echo $form->field($model, 'status')
	->checkbox()
	->label($model->getAttributeLabel('status')); ?>

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