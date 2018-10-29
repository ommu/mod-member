<?php
/**
 * Member Profile Documents (member-profile-document)
 * @var $this yii\web\View
 * @var $this ommu\member\controllers\ProfileDocumentController
 * @var $model ommu\member\models\search\MemberProfileDocument
 * @var $form yii\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 2 October 2018, 11:36 WIB
 * @link https://github.com/ommu/mod-member
 *
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use ommu\member\models\MemberProfile;
use ommu\member\models\MemberDocumentType;
?>

<div class="member-profile-document-search search-form">
	<?php $form = ActiveForm::begin([
		'action' => ['index'],
		'method' => 'get',
	]); ?>
	<?php $profile_id = MemberProfile::getProfile();
	echo $form->field($model, 'profile_id')
		->dropDownList($profile_id, ['prompt'=>'']);?>

	<?php $document_id = MemberDocumentType::getType();
	echo $form->field($model, 'document_id')
		->dropDownList($document_id, ['prompt'=>'']);?>

		<?php echo $form->field($model, 'required')
			->checkbox();?>

		<?php echo $form->field($model, 'creation_date')
			->input('date');?>

		<?php echo $form->field($model, 'creation_search');?>

		<?php echo $form->field($model, 'modified_date')
			->input('date');?>

		<?php echo $form->field($model, 'modified_search');?>

		<?php echo $form->field($model, 'updated_date')
			->input('date');?>

		<?php echo $form->field($model, 'publish')
			->checkbox();?>

		<div class="form-group">
			<?php echo Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
			<?php echo Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
		</div>
	<?php ActiveForm::end(); ?>
</div>
