<?php
/**
 * Member Follower Histories (member-follower-history)
 * @var $this app\components\View
 * @var $this ommu\member\controllers\history\FollowerController
 * @var $model ommu\member\models\MemberFollowerHistory
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2018 OMMU (www.ommu.id)
 * @created date 31 October 2018, 06:25 WIB
 * @link https://github.com/ommu/mod-member
 *
 */

use yii\helpers\Url;
use yii\widgets\DetailView;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Follower Histories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->follower->member->displayname;

if (!$small) {
    $this->params['menu']['content'] = [
        ['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id' => $model->id]), 'htmlOptions' => ['data-confirm' => Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method' => 'post', 'class' => 'btn btn-danger'], 'icon' => 'trash'],
    ];
} ?>

<div class="member-follower-history-view">

<?php
$attributes = [
	[
		'attribute' => 'id',
		'value' => $model->id,
		'visible' => !$small,
	],
	[
		'attribute' => 'publish',
		'value' => $model->publish ? Yii::t('app', 'Follow') : Yii::t('app', 'Unfollow'),
	],
	[
		'attribute' => 'profile_search',
		'value' => isset($model->member) ? $model->member->profile->title->message : '-',
	],
	[
		'attribute' => 'member_search',
		'value' => isset($model->follower) ? $model->follower->member->displayname : '-',
	],
	[
		'attribute' => 'userDisplayname',
		'value' => isset($model->follower) ? $model->follower->user->displayname : '-',
	],
	[
		'attribute' => 'creation_date',
		'value' => Yii::$app->formatter->asDatetime($model->creation_date, 'medium'),
		'visible' => !$small,
	],
	[
		'attribute' => 'creationDisplayname',
		'value' => isset($model->creation) ? $model->creation->displayname : '-',
	],
];

echo DetailView::widget([
	'model' => $model,
	'options' => [
		'class' => 'table table-striped detail-view',
	],
	'attributes' => $attributes,
]); ?>

</div>