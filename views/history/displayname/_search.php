<?php
/**
 * Member History Displaynames (member-history-displayname)
 * @var $this app\components\View
 * @var $this ommu\member\controllers\history\DisplaynameController
 * @var $model ommu\member\models\search\MemberHistoryDisplayname
 * @var $form yii\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2018 OMMU (www.ommu.id)
 * @created date 30 October 2018, 23:03 WIB
 * @link https://github.com/ommu/mod-member
 *
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use ommu\member\models\MemberProfile;
?>

<div class="member-history-displayname-search search-form">

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

		<?php echo $form->field($model, 'displayname');?>

		<?php echo $form->field($model, 'updated_date')
			->input('date');?>

		<?php echo $form->field($model, 'updated_search');?>

		<div class="form-group">
			<?php echo Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']); ?>
			<?php echo Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']); ?>
		</div>

	<?php ActiveForm::end(); ?>

</div>
