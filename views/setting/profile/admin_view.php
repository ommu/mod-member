<?php
/**
 * Member Profiles (member-profile)
 * @var $this app\components\View
 * @var $this ommu\member\controllers\setting\ProfileController
 * @var $model ommu\member\models\MemberProfile
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 2 October 2018, 09:48 WIB
 * @link https://github.com/ommu/mod-member
 *
 */

use yii\helpers\Url;
use yii\widgets\DetailView;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Profiles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->title->message;

if(!$small) {
$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Update'), 'url' => Url::to(['update', 'id'=>$model->profile_id]), 'htmlOptions' => ['class'=>'modal-btn'], 'icon' => 'pencil', 'htmlOptions' => ['class'=>'btn btn-primary']],
	['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id'=>$model->profile_id]), 'htmlOptions' => ['data-confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method'=>'post', 'class'=>'btn btn-danger'], 'icon' => 'trash'],
];
} ?>

<div class="member-profile-view">

<?php echo DetailView::widget([
	'model' => $model,
	'options' => [
		'class'=>'table table-striped detail-view',
	],
	'attributes' => [
		'profile_id',
		[
			'attribute' => 'publish',
			'value' => $this->quickAction(Url::to(['publish', 'id'=>$model->primaryKey]), $model->publish, 'Enable,Disable'),
			'format' => 'raw',
		],
		[
			'attribute' => 'profile_name_i',
			'value' => $model->profile_name_i,
		],
		[
			'attribute' => 'profile_desc_i',
			'value' => $model->profile_desc_i,
		],
		[
			'attribute' => 'assignment_roles',
			'value' => $this->formatFileType($model->assignment_roles, false, '<br/>'),
			'format' => 'html',
		],
		[
			'attribute' => 'profile_personal',
			'value' => $this->filterYesNo($model->profile_personal),
		],
		[
			'attribute' => 'multiple_user',
			'value' => $this->filterYesNo($model->multiple_user),
		],
		'user_limit',
		[
			'attribute' => 'creation_date',
			'value' => Yii::$app->formatter->asDatetime($model->creation_date, 'medium'),
			'visible' => !$small,
		],
		[
			'attribute' => 'creation_search',
			'value' => isset($model->creation) ? $model->creation->displayname : '-',
		],
		[
			'attribute' => 'modified_date',
			'value' => Yii::$app->formatter->asDatetime($model->modified_date, 'medium'),
			'visible' => !$small,
		],
		[
			'attribute' => 'modified_search',
			'value' => isset($model->modified) ? $model->modified->displayname : '-',
		],
		[
			'attribute' => 'updated_date',
			'value' => Yii::$app->formatter->asDatetime($model->updated_date, 'medium'),
			'visible' => !$small,
		],
	],
]) ?>

</div>