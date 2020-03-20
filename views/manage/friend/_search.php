<?php
/**
 * Member Friends (member-friends)
 * @var $this app\components\View
 * @var $this ommu\member\controllers\manage\FriendController
 * @var $model ommu\member\models\search\MemberFriends
 * @var $form yii\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 OMMU (www.ommu.id)
 * @created date 31 October 2018, 13:53 WIB
 * @link https://github.com/ommu/mod-member
 *
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use ommu\member\models\MemberFriendType;
?>

<div class="member-friends-search search-form">

	<?php $form = ActiveForm::begin([
		'action' => ['index'],
		'method' => 'get',
		'options' => [
			'data-pjax' => 1
		],
	]); ?>

		<?php $type = MemberFriendType::getType();
		echo $form->field($model, 'type_id')
			->dropDownList($type, ['prompt'=>'']);?>

		<?php echo $form->field($model, 'userDisplayname');?>

		<?php echo $form->field($model, 'request_search');?>

		<?php echo $form->field($model, 'request_date')
			->input('date');?>

		<?php echo $form->field($model, 'modified_date')
			->input('date');?>

		<?php echo $form->field($model, 'modifiedDisplayname');?>

		<div class="form-group">
			<?php echo Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']); ?>
			<?php echo Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']); ?>
		</div>

	<?php ActiveForm::end(); ?>

</div>
