<?php
/**
 * Members (members)
 * @var $this app\components\View
 * @var $this ommu\member\controllers\manage\AdminController
 * @var $model ommu\member\models\search\Members
 * @var $form yii\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 30 October 2018, 22:51 WIB
 * @modified date 3 November 2018, 18:44 WIB
 * @link https://github.com/ommu/mod-member
 *
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use ommu\member\models\Members;
use ommu\member\models\MemberProfile;
?>

<div class="members-search search-form">

	<?php $form = ActiveForm::begin([
		'action' => ['index'],
		'method' => 'get',
		'options' => [
			'data-pjax' => 1
		],
	]); ?>

		<?php $profile = MemberProfile::getProfile();
		echo $form->field($model, 'profile_id')
			->dropDownList($profile, ['prompt'=>'']);?>

		<?php echo $form->field($model, 'username');?>

		<?php echo $form->field($model, 'displayname');?>

		<?php echo $form->field($model, 'photo_header');?>

		<?php echo $form->field($model, 'photo_profile');?>

		<?php echo $form->field($model, 'short_biography');?>

		<?php echo $form->field($model, 'approved_date')
			->input('date');?>

		<?php echo $form->field($model, 'approved_search');?>

		<?php echo $form->field($model, 'creation_date')
			->input('date');?>

		<?php echo $form->field($model, 'creation_search');?>

		<?php echo $form->field($model, 'modified_date')
			->input('date');?>

		<?php echo $form->field($model, 'modified_search');?>

		<?php echo $form->field($model, 'updated_date')
			->input('date');?>

		<?php echo $form->field($model, 'approved')
			->dropDownList($this->filterYesNo(), ['prompt'=>'']);?>

		<?php $memberPrivate = Members::getMemberPrivate();
			echo $form->field($model, 'member_private')
			->dropDownList($memberPrivate, ['prompt'=>'']);?>

		<?php echo $form->field($model, 'publish')
			->dropDownList($this->filterYesNo(), ['prompt'=>'']);?>

		<div class="form-group">
			<?php echo Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
			<?php echo Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
		</div>

	<?php ActiveForm::end(); ?>

</div>