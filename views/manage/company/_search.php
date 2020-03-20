<?php
/**
 * Member Companies (member-company)
 * @var $this app\components\View
 * @var $this ommu\member\controllers\manage\CompanyController
 * @var $model ommu\member\models\search\MemberCompany
 * @var $form yii\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 OMMU (www.ommu.id)
 * @created date 31 October 2018, 15:48 WIB
 * @link https://github.com/ommu/mod-member
 *
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use ommu\member\models\MemberCompanyType;
use ommu\member\models\MemberProfileCategory;
?>

<div class="member-company-search search-form">

	<?php $form = ActiveForm::begin([
		'action' => ['index'],
		'method' => 'get',
		'options' => [
			'data-pjax' => 1
		],
	]); ?>

		<?php echo $form->field($model, 'member_i');?>

		<?php $companyType = MemberCompanyType::getType();
		echo $form->field($model, 'company_type_id')
			->dropDownList($companyType, ['prompt'=>'']);?>

		<?php $companyCategory = MemberProfileCategory::getCategory();
		echo $form->field($model, 'company_cat_id')
			->dropDownList($companyCategory, ['prompt'=>'']);?>

		<?php echo $form->field($model, 'info_intro');?>

		<?php echo $form->field($model, 'info_article');?>

		<?php echo $form->field($model, 'company_address');?>

		<?php echo $form->field($model, 'company_country_id');?>

		<?php echo $form->field($model, 'company_province_id');?>

		<?php echo $form->field($model, 'company_city_id');?>

		<?php echo $form->field($model, 'company_district');?>

		<?php echo $form->field($model, 'company_village');?>

		<?php echo $form->field($model, 'company_zipcode');?>

		<?php echo $form->field($model, 'creation_date')
			->input('date');?>

		<?php echo $form->field($model, 'creationDisplayname');?>

		<?php echo $form->field($model, 'modified_date')
			->input('date');?>

		<?php echo $form->field($model, 'modifiedDisplayname');?>

		<?php echo $form->field($model, 'updated_date')
			->input('date');?>

		<div class="form-group">
			<?php echo Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']); ?>
			<?php echo Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']); ?>
		</div>

	<?php ActiveForm::end(); ?>

</div>
