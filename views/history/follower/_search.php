<?php
/**
 * Member Follower Histories (member-follower-history)
 * @var $this yii\web\View
 * @var $this ommu\member\controllers\history\FollowerController
 * @var $model ommu\member\models\search\MemberFollowerHistory
 * @var $form yii\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 31 October 2018, 06:25 WIB
 * @link https://github.com/ommu/mod-member
 *
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use ommu\member\models\MemberProfile;
?>

<div class="member-follower-history-search search-form">

	<?php $form = ActiveForm::begin([
		'action' => ['index'],
		'method' => 'get',
		'options' => [
			'data-pjax' => 1
		],
	]); ?>

		<?php $profile = MemberProfile::getProfile();
		echo $form->field($model, 'profile_search')
			->dropDownList($profile, ['prompt'=>'']);?>

		<?php echo $form->field($model, 'member_search');?>

		<?php echo $form->field($model, 'user_search');?>

		<?php echo $form->field($model, 'creation_date')
			->input('date');?>

		<?php echo $form->field($model, 'creation_search');?>

		<?php echo $form->field($model, 'publish')
			->dropDownList($this->filterYesNo(), ['prompt'=>'']);?>

		<div class="form-group">
			<?php echo Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
			<?php echo Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
		</div>

	<?php ActiveForm::end(); ?>

</div>
