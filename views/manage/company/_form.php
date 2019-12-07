<?php
/**
 * Member Companies (member-company)
 * @var $this app\components\View
 * @var $this ommu\member\controllers\manage\CompanyController
 * @var $model ommu\member\models\MemberCompany
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 31 October 2018, 15:48 WIB
 * @link https://github.com/ommu/mod-member
 *
 */

use yii\helpers\Html;
use app\components\widgets\ActiveForm;
use yii\redactor\widgets\Redactor;
use ommu\member\models\MemberCompanyType;
use ommu\member\models\MemberProfileCategory;
use ommu\member\models\Members;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\jui\AutoComplete;
use app\models\CoreZoneCity;
use app\models\CoreZoneProvince;
use app\models\CoreZoneCountry;

$redactorOptions = [
	'imageManagerJson' => ['/redactor/upload/image-json'],
	'imageUpload' => ['/redactor/upload/image'],
	'fileUpload' => ['/redactor/upload/file'],
	'plugins' => ['clips', 'fontcolor', 'imagemanager']
];
?>

<div class="member-company-form">

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

<?php echo $form->field($member, 'username')
	->textInput(['maxlength'=>true])
	->label($member->getAttributeLabel('username')); ?>

<hr/>

<?php $companyType = MemberCompanyType::getType();
echo $form->field($model, 'company_type_id')
	->dropDownList($companyType, ['prompt'=>''])
	->label($model->getAttributeLabel('company_type_id')); ?>

<?php echo $form->field($member, 'displayname')
	->textInput(['maxlength'=>true])
	->label($member->getAttributeLabel('displayname')); ?>

<?php $companyCategory = MemberProfileCategory::getCategory();
echo $form->field($model, 'company_cat_id')
	->dropDownList($companyCategory, ['prompt'=>''])
	->label($model->getAttributeLabel('company_cat_id')); ?>

<hr/>

<?php $uploadPath = join('/', [Members::getUploadPath(false), $member->member_id]);
$photoHeader = !$member->isNewRecord && $member->old_photo_header_i != '' ? Html::img(Url::to(join('/', ['@webpublic', $uploadPath, $member->old_photo_header_i])), ['alt'=>$model->old_photo_header_i, 'class'=>'mb-3']) : '';
echo $form->field($member, 'photo_header', ['template' => '{label}{beginWrapper}<div>'.$photoHeader.'</div>{input}{error}{hint}{endWrapper}'])
	->fileInput()
	->label($member->getAttributeLabel('photo_header')); ?>

<?php $uploadPath = join('/', [Members::getUploadPath(false), $member->member_id]);
$photoProfile = !$member->isNewRecord && $member->old_photo_profile_i != '' ? Html::img(Url::to(join('/', ['@webpublic', $uploadPath, $member->old_photo_profile_i])), ['alt'=>$model->old_photo_profile_i, 'class'=>'mb-3']) : '';
echo $form->field($member, 'photo_profile', ['template' => '{label}{beginWrapper}<div>'.$photoProfile.'</div>{input}{error}{hint}{endWrapper}'])
	->fileInput()
	->label($member->getAttributeLabel('photo_profile')); ?>

<?php /*
<div class="form-group field-photo_header">
	<?php echo $form->field($member, 'photo_header', ['template' => '{label}', 'options' => ['tag' => null]])
		->label($member->getAttributeLabel('photo_header')); ?>
	<div class="col-md-6 col-sm-9 col-xs-12">
		<?php echo !$member->isNewRecord && $member->old_photo_header_i != '' ? Html::img(Url::to(join('/', ['@webpublic', Members::getUploadPath(false), $member->member_id, $member->old_photo_header_i])), ['alt'=>$model->old_photo_header_i, 'class'=>'mb-3']) : '';?>
		<?php echo $form->field($member, 'photo_header', ['template' => '{input}{error}'])
			->fileInput()
			->label($member->getAttributeLabel('photo_header')); ?>
	</div>
</div>

<div class="form-group field-photo_profile">
	<?php echo $form->field($member, 'photo_profile', ['template' => '{label}', 'options' => ['tag' => null]])
		->label($member->getAttributeLabel('photo_profile')); ?>
	<div class="col-md-6 col-sm-9 col-xs-12">
		<?php echo !$member->isNewRecord && $member->old_photo_profile_i != '' ? Html::img(Url::to(join('/', ['@webpublic', Members::getUploadPath(false), $member->member_id, $member->old_photo_profile_i])), ['alt'=>$model->old_photo_profile_i, 'class'=>'mb-3']) : '';?>
		<?php echo $form->field($member, 'photo_profile', ['template' => '{input}{error}'])
			->fileInput()
			->label($member->getAttributeLabel('photo_profile')); ?>
	</div>
</div>
*/?>

<hr/>

<?php echo $form->field($model, 'info_intro')
	->textarea(['rows'=>6, 'cols'=>50])
	->label($model->getAttributeLabel('info_intro')); ?>

<?php echo $form->field($model, 'info_article')
	->textarea(['rows'=>6, 'cols'=>50])
	->widget(Redactor::className(), ['clientOptions' => $redactorOptions])
	->label($model->getAttributeLabel('info_article')); ?>

<hr/>

<?php $company_village = $form->field($model, 'company_village', ['template' => '{beginWrapper}{input}{error}{endWrapper}', 'horizontalCssClasses' => ['wrapper'=>'col-md-3 col-sm-4 col-xs-6 col-sm-offset-3'], 'options' => ['tag' => null]])
	//->textInput(['maxlength'=>true, 'placeholder'=>$model->getAttributeLabel('company_village')])
	->widget(AutoComplete::className(), [
		'options' => [
			'placeholder' => 'Your village. *auto suggest',
			'data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Autosuggest '.$model->getAttributeLabel('company_village').', ketikan minimal 2 huruf',
			'class' => 'ui-autocomplete-input form-control'
		],
		'clientOptions' => [
			'source' => Url::to(['/village/suggest', 'extend'=>'village_name,district_name,city_id,province_id,country_id']),
			'minLength' => 2,
			'select' => new JsExpression("function(event, ui) {
				\$('.field-company_address #company_district').val(ui.item.district_name);
				\$('.field-company_city_id #company_city_id').val(ui.item.city_id);
				\$('.field-company_province_id #company_province_id').val(ui.item.province_id);
				\$('.field-company_country_id #company_country_id').val(ui.item.country_id);
				\$(event.target).val(ui.item.village_name);
				return false;
			}"),
		]
	])
	->label($model->getAttributeLabel('company_village')); ?>

<?php $company_district = $form->field($model, 'company_district', ['template' => '{beginWrapper}{input}{error}{endWrapper}', 'horizontalCssClasses' => ['wrapper'=>'col-md-3 col-sm-5 col-xs-6'], 'options' => ['tag' => null]])
	//->textInput(['maxlength'=>true, 'placeholder'=>$model->getAttributeLabel('company_district')])
	->widget(AutoComplete::className(), [
		'options' => [
			'placeholder' => 'Your district. *auto suggest',
			'data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Autosuggest '.$model->getAttributeLabel('company_district').', ketikan minimal 2 huruf',
			'class' => 'ui-autocomplete-input form-control'
		],
		'clientOptions' => [
			'source' => Url::to(['/district/suggest', 'extend'=>'district_name,city_id,province_id,country_id']),
			'minLength' => 2,
			'select' => new JsExpression("function(event, ui) {
				\$('.field-company_city_id #company_city_id').val(ui.item.city_id);
				\$('.field-company_province_id #company_province_id').val(ui.item.province_id);
				\$('.field-company_country_id #company_country_id').val(ui.item.country_id);
				\$(event.target).val(ui.item.district_name);
				return false;
			}"),
		]
	])
	->label($model->getAttributeLabel('company_district')); ?>

<?php echo $form->field($model, 'company_address', ['template' => '{label}{beginWrapper}{input}{endWrapper}'.$company_village.$company_district.'{error}', 'horizontalCssClasses' => ['error'=>'col-md-6 col-sm-9 col-xs-12 col-sm-offset-3']])
	->textarea(['rows'=>6, 'cols'=>50])
	->label($model->getAttributeLabel('company_address')); ?>

<?php $companyCity = CoreZoneCity::getCity(1);
echo $form->field($model, 'company_city_id')
	->dropDownList($companyCity, ['prompt'=>''])
	->label($model->getAttributeLabel('company_city_id')); ?>

<?php $companyProvince = CoreZoneProvince::getProvince(1);
echo $form->field($model, 'company_province_id')
	->dropDownList($companyProvince, ['prompt'=>''])
	->label($model->getAttributeLabel('company_province_id')); ?>

<?php $companyCountry = CoreZoneCountry::getCountry();
echo $form->field($model, 'company_country_id')
	->dropDownList($companyCountry, ['prompt'=>''])
	->label($model->getAttributeLabel('company_country_id')); ?>

<?php if(!$model->getErrors() && $model->company_zipcode == 0)
	$model->company_zipcode = '';
echo $form->field($model, 'company_zipcode')
	->textInput(['type'=>'number', 'min'=>'1'])
	->label($model->getAttributeLabel('company_zipcode')); ?>

<hr/>

<?php $memberPrivate = Members::getMemberPrivate();
if($member->isNewRecord && !$member->getErrors())
	$member->member_private = 0;
echo $form->field($member, 'member_private')
	->dropDownList($memberPrivate, ['prompt'=>''])
	->label($member->getAttributeLabel('member_private')); ?>

<?php echo $form->field($member, 'approved')
	->checkbox()
	->label($member->getAttributeLabel('approved')); ?>

<?php echo $form->field($member, 'publish')
	->checkbox()
	->label($member->getAttributeLabel('publish')); ?>

<hr/>

<?php echo $form->field($model, 'submitButton')
	->submitButton(); ?>

<?php ActiveForm::end(); ?>

</div>