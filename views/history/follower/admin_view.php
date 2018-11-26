<?php
/**
 * Member Follower Histories (member-follower-history)
 * @var $this yii\web\View
 * @var $this ommu\member\controllers\history\FollowerController
 * @var $model ommu\member\models\MemberFollowerHistory
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 31 October 2018, 06:25 WIB
 * @link https://github.com/ommu/mod-member
 *
 */

use yii\helpers\Url;
use yii\widgets\DetailView;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Follower Histories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->follower->member->displayname;

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Back To Manage'), 'url' => Url::to(['index']), 'icon' => 'table'],
	['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id'=>$model->id]), 'confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'), 'method'=>'post', 'icon' => 'trash'],
];
?>

<div class="member-follower-history-view">

<?php echo DetailView::widget([
	'model' => $model,
	'options' => [
		'class'=>'table table-striped detail-view',
	],
	'attributes' => [
		'id',
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
			'attribute' => 'user_search',
			'value' => isset($model->follower) ? $model->follower->user->displayname : '-',
		],
		[
			'attribute' => 'creation_date',
			'value' => !in_array($model->creation_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00']) ? Yii::$app->formatter->format($model->creation_date, 'datetime') : '-',
		],
		[
			'attribute' => 'creation_search',
			'value' => isset($model->creation) ? $model->creation->displayname : '-',
		],
	],
]) ?>

</div>