<?php
/**
 * Member History Usernames (member-history-username)
 * @var $this app\components\View
 * @var $this ommu\member\controllers\history\UsernameController
 * @var $model ommu\member\models\MemberHistoryUsername
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 OMMU (www.ommu.id)
 * @created date 30 October 2018, 23:04 WIB
 * @link https://github.com/ommu/mod-member
 *
 */

use yii\helpers\Url;
use yii\widgets\DetailView;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'History Usernames'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->displayname;

if (!$small) {
    $this->params['menu']['content'] = [
        ['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id'=>$model->id]), 'htmlOptions' => ['data-confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method'=>'post', 'class'=>'btn btn-danger'], 'icon' => 'trash'],
    ];
} ?>

<div class="member-history-username-view">

<?php
$attributes = [
	[
		'attribute' => 'id',
		'value' => $model->id,
		'visible' => !$small,
	],
	[
		'attribute' => 'profile_search',
		'value' => isset($model->member) ? $model->member->profile->title->message : '-',
	],
	[
		'attribute' => 'member_search',
		'value' => isset($model->member) ? $model->member->displayname : '-',
	],
	[
		'attribute' => 'username',
		'value' => $model->username ? $model->username : '-',
	],
	[
		'attribute' => 'updated_date',
		'value' => Yii::$app->formatter->asDatetime($model->updated_date, 'medium'),
		'visible' => !$small,
	],
	[
		'attribute' => 'updated_search',
		'value' => isset($model->updated) ? $model->updated->displayname : '-',
	],
];

echo DetailView::widget([
	'model' => $model,
	'options' => [
		'class'=>'table table-striped detail-view',
	],
	'attributes' => $attributes,
]); ?>

</div>