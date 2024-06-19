<?php
/**
 * Member Friends (member-friends)
 * @var $this app\components\View
 * @var $this ommu\member\controllers\manage\FriendController
 * @var $model ommu\member\models\MemberFriends
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2018 OMMU (www.ommu.id)
 * @created date 31 October 2018, 13:53 WIB
 * @link https://github.com/ommu/mod-member
 *
 */

use yii\helpers\Html;
use app\components\widgets\ActiveForm;
use ommu\member\models\MemberFriendType;
?>

<div class="member-friends-form">

<?php $form = ActiveForm::begin([
	'options' => ['class' => 'form-horizontal form-label-left'],
	'enableClientValidation' => true,
	'enableAjaxValidation' => false,
	//'enableClientScript' => true,
	'fieldConfig' => [
		'errorOptions' => [
			'encode' => false,
		],
	],
]); ?>

<?php //echo $form->errorSummary($model);?>

<?php $type = MemberFriendType::getType();
echo $form->field($model, 'type_id')
	->dropDownList($type, ['prompt' => ''])
	->label($model->getAttributeLabel('type_id')); ?>

<?php echo $form->field($model, 'user_id')
	->textInput(['type' => 'number', 'min' => '1'])
	->label($model->getAttributeLabel('user_id')); ?>

<?php echo $form->field($model, 'request_id')
	->textInput(['type' => 'number', 'min' => '1'])
	->label($model->getAttributeLabel('request_id')); ?>

<hr/>

<?php echo $form->field($model, 'submitButton')
	->submitButton(); ?>

<?php ActiveForm::end(); ?>

</div>