<?php
/**
 * Member Friend Histories (member-friend-history)
 * @var $this yii\web\View
 * @var $this ommu\member\controllers\history\FriendController
 * @var $model ommu\member\models\search\MemberFriendHistory
 * @var $form yii\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 31 October 2018, 05:45 WIB
 * @link https://github.com/ommu/mod-member
 *
 */

use yii\helpers\Html;
use app\components\ActiveForm;
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

		<?php echo $form->field($model, 'creation_search');?>

		<div class="form-group">
			<?php echo Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
			<?php echo Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
		</div>

	<?php ActiveForm::end(); ?>

</div>
