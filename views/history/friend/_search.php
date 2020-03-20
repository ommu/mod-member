<?php
/**
 * Member Friend Histories (member-friend-history)
 * @var $this app\components\View
 * @var $this ommu\member\controllers\history\FriendController
 * @var $model ommu\member\models\search\MemberFriendHistory
 * @var $form yii\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 OMMU (www.ommu.id)
 * @created date 31 October 2018, 05:45 WIB
 * @link https://github.com/ommu/mod-member
 *
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use ommu\member\models\MemberFriendType;
?>

<div class="member-friend-history-search search-form">

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

		<?php echo $form->field($model, 'st_user_search');?>

		<?php echo $form->field($model, 'nd_user_search');?>

		<?php echo $form->field($model, 'creation_date')
			->input('date');?>

		<?php echo $form->field($model, 'creationDisplayname');?>

		<div class="form-group">
			<?php echo Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']); ?>
			<?php echo Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']); ?>
		</div>

	<?php ActiveForm::end(); ?>

</div>
