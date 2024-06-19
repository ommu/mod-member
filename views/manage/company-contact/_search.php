<?php
/**
 * Member Company Contacts (member-company-contact)
 * @var $this app\components\View
 * @var $this ommu\member\controllers\manage\CompanyContactController
 * @var $model ommu\member\models\search\MemberCompanyContact
 * @var $form yii\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2018 OMMU (www.ommu.id)
 * @created date 1 November 2018, 19:49 WIB
 * @link https://github.com/ommu/mod-member
 *
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use ommu\member\models\MemberContactCategory;
use ommu\member\models\MemberProfile;
?>

<div class="member-company-contact-search search-form">

	<?php $form = ActiveForm::begin([
		'action' => ['index'],
		'method' => 'get',
		'options' => [
			'data-pjax' => 1
		],
	]); ?>

		<?php $profile = MemberProfile::getProfile();
		echo $form->field($model, 'profile_search')
			->dropDownList($profile, ['prompt' => '']);?>

		<?php echo $form->field($model, 'member_search');?>

		<?php $category = MemberContactCategory::getCategory();
		echo $form->field($model, 'contact_cat_id')
			->dropDownList($category, ['prompt' => '']);?>

		<?php echo $form->field($model, 'contact_value');?>

		<?php echo $form->field($model, 'verified_date')
			->input('date');?>

		<?php echo $form->field($model, 'verified_search');?>

		<?php echo $form->field($model, 'creation_date')
			->input('date');?>

		<?php echo $form->field($model, 'creationDisplayname');?>

		<?php echo $form->field($model, 'modified_date')
			->input('date');?>

		<?php echo $form->field($model, 'modifiedDisplayname');?>

		<?php echo $form->field($model, 'updated_date')
			->input('date');?>

		<?php echo $form->field($model, 'status')
			->dropDownList($model->filterYesNo(), ['prompt' => '']);?>

		<?php echo $form->field($model, 'publish')
			->dropDownList($model->filterYesNo(), ['prompt' => '']);?>

		<div class="form-group">
			<?php echo Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']); ?>
			<?php echo Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']); ?>
		</div>

	<?php ActiveForm::end(); ?>

</div>
