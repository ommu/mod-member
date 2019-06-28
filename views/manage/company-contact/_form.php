<?php
/**
 * Member Company Contacts (member-company-contact)
 * @var $this app\components\View
 * @var $this ommu\member\controllers\manage\CompanyContactController
 * @var $model ommu\member\models\MemberCompanyContact
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 1 November 2018, 19:49 WIB
 * @link https://github.com/ommu/mod-member
 *
 */

use yii\helpers\Html;
use app\components\widgets\ActiveForm;
use ommu\member\models\MemberContactCategory;
?>

<div class="member-company-contact-form">

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

<?php $category = MemberContactCategory::getCategory();
echo $form->field($model, 'contact_cat_id')
	->dropDownList($category, ['prompt'=>''])
	->label($model->getAttributeLabel('contact_cat_id')); ?>

<?php echo $form->field($model, 'contact_value')
	->textarea(['rows'=>6, 'cols'=>50])
	->label($model->getAttributeLabel('contact_value')); ?>

<?php echo $form->field($model, 'status')
	->checkbox()
	->label($model->getAttributeLabel('status')); ?>

<?php echo $form->field($model, 'publish')
	->checkbox()
	->label($model->getAttributeLabel('publish')); ?>

<div class="ln_solid"></div>

<?php echo $form->field($model, 'submitButton')
	->submitButton(); ?>

<?php ActiveForm::end(); ?>

</div>