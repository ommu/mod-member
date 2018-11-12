<?php
/**
 * Member Companies (member-company)
 * @var $this yii\web\View
 * @var $this ommu\member\controllers\manage\CompanyController
 * @var $model ommu\member\models\MemberCompany
 * @var $form yii\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 31 October 2018, 15:48 WIB
 * @link https://github.com/ommu/mod-member
 *
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
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
	'plugins' => ['clips', 'fontcolor','imagemanager']
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
]); ?>

<?php //echo $form->errorSummary($model);?>

<?php echo $form->field($member, 'username', ['template' => '{label}<div class="col-md-6 col-sm-9 col-xs-12">{input}{error}</div>'])
	->textInput(['maxlength'=>true])
	->label($member->getAttributeLabel('username'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<div class="ln_solid"></div>

<?php $companyType = MemberCompanyType::getType();
echo $form->field($model, 'company_type_id', ['template' => '{label}<div class="col-md-6 col-sm-9 col-xs-12">{input}{error}</div>'])
	->dropDownList($companyType, ['prompt'=>''])
	->label($model->getAttributeLabel('company_type_id'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php echo $form->field($member, 'displayname', ['template' => '{label}<div class="col-md-6 col-sm-9 col-xs-12">{input}{error}</div>'])
	->textInput(['maxlength'=>true])
	->label($member->getAttributeLabel('displayname'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php $companyCategory = MemberProfileCategory::getCategory();
echo $form->field($model, 'company_cat_id', ['template' => '{label}<div class="col-md-6 col-sm-9 col-xs-12">{input}{error}</div>'])
	->dropDownList($companyCategory, ['prompt'=>''])
	->label($model->getAttributeLabel('company_cat_id'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<div class="ln_solid"></div>

<div class="form-group field-photo_header">
	<?php echo $form->field($member, 'photo_header', ['template' => '{label}', 'options' => ['tag' => null]])
		->label($member->getAttributeLabel('photo_header'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>
	<div class="col-md-6 col-sm-9 col-xs-12">
		<?php echo !$member->isNewRecord && $member->old_photo_header_i != '' ? Html::img(join('/', [Url::Base(), Members::getUploadPath(false), $member->member_id, $member->old_photo_header_i]), ['class'=>'mb-15', 'width'=>'100%']) : '';?>
		<?php echo $form->field($member, 'photo_header', ['template' => '{input}{error}'])
			->fileInput()
			->label($member->getAttributeLabel('photo_header'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>
	</div>
</div>

<div class="form-group field-photo_profile">
	<?php echo $form->field($member, 'photo_profile', ['template' => '{label}', 'options' => ['tag' => null]])
		->label($member->getAttributeLabel('photo_profile'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>
	<div class="col-md-6 col-sm-9 col-xs-12">
		<?php echo !$member->isNewRecord && $member->old_photo_profile_i != '' ? Html::img(join('/', [Url::Base(), Members::getUploadPath(false), $member->member_id, $member->old_photo_profile_i]), ['class'=>'mb-15', 'width'=>'100%']) : '';?>
		<?php echo $form->field($member, 'photo_profile', ['template' => '{input}{error}'])
			->fileInput()
			->label($member->getAttributeLabel('photo_profile'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>
	</div>
</div>

<div class="ln_solid"></div>

<?php echo $form->field($model, 'info_intro', ['template' => '{label}<div class="col-md-6 col-sm-9 col-xs-12">{input}{error}</div>'])
	->textarea(['rows'=>2, 'rows'=>6])
	->label($model->getAttributeLabel('info_intro'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php echo $form->field($model, 'info_article', ['template' => '{label}<div class="col-md-6 col-sm-9 col-xs-12">{input}{error}</div>'])
	->textarea(['rows'=>2,'rows'=>6])
	->widget(Redactor::className(), ['clientOptions' => $redactorOptions])
	->label($model->getAttributeLabel('info_article'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<div class="ln_solid"></div>

<?php $company_village = $form->field($model, 'company_village', ['template' => '<div class="col-md-3 col-sm-4 col-xs-6 col-sm-offset-3 pt-10">{input}{error}</div>', 'options' => ['tag' => null]])
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
	->label($model->getAttributeLabel('company_village'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php $company_district = $form->field($model, 'company_district', ['template' => '<div class="col-md-3 col-sm-5 col-xs-6 pt-10">{input}{error}</div>', 'options' => ['tag' => null]])
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
	->label($model->getAttributeLabel('company_district'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php echo $form->field($model, 'company_address', ['template' => '{label}<div class="col-md-6 col-sm-9 col-xs-12">{input}</div>'.$company_village.$company_district.'<div class="col-md-6 col-sm-9 col-xs-12 col-sm-offset-3">{error}</div>'])
	->textarea(['rows'=>2, 'rows'=>6])
	->label($model->getAttributeLabel('company_address'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php $companyCity = CoreZoneCity::getCity(1);
echo $form->field($model, 'company_city_id', ['template' => '{label}<div class="col-md-6 col-sm-9 col-xs-12">{input}{error}</div>'])
	->dropDownList($companyCity, ['prompt'=>''])
	->label($model->getAttributeLabel('company_city_id'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php $companyProvince = CoreZoneProvince::getProvince(1);
echo $form->field($model, 'company_province_id', ['template' => '{label}<div class="col-md-6 col-sm-9 col-xs-12">{input}{error}</div>'])
	->dropDownList($companyProvince, ['prompt'=>''])
	->label($model->getAttributeLabel('company_province_id'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php $companyCountry = CoreZoneCountry::getCountry();
echo $form->field($model, 'company_country_id', ['template' => '{label}<div class="col-md-6 col-sm-9 col-xs-12">{input}{error}</div>'])
	->dropDownList($companyCountry, ['prompt'=>''])
	->label($model->getAttributeLabel('company_country_id'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php if(!$model->getErrors() && $model->company_zipcode == 0)
	$model->company_zipcode = '';
echo $form->field($model, 'company_zipcode', ['template' => '{label}<div class="col-md-6 col-sm-9 col-xs-12">{input}{error}</div>'])
	->textInput(['type'=>'number', 'min'=>'1'])
	->label($model->getAttributeLabel('company_zipcode'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<div class="ln_solid"></div>

<?php $memberPrivate = Members::getMemberPrivate();
if($member->isNewRecord && !$member->getErrors())
	$member->member_private = 0;
echo $form->field($member, 'member_private', ['template' => '{label}<div class="col-md-6 col-sm-9 col-xs-12">{input}{error}</div>'])
	->dropDownList($memberPrivate, ['prompt'=>''])
	->label($member->getAttributeLabel('member_private'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php echo $form->field($member, 'approved', ['template' => '{label}<div class="col-md-6 col-sm-9 col-xs-12 checkbox">{input}{error}</div>'])
	->checkbox(['label'=>''])
	->label($member->getAttributeLabel('approved'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php echo $form->field($member, 'publish', ['template' => '{label}<div class="col-md-6 col-sm-9 col-xs-12 checkbox">{input}{error}</div>'])
	->checkbox(['label'=>''])
	->label($member->getAttributeLabel('publish'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<div class="ln_solid"></div>
<div class="form-group">
	<div class="col-md-6 col-sm-9 col-xs-12 col-sm-offset-3">
		<?php echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']); ?>
	</div>
</div>

<?php ActiveForm::end(); ?>

</div>