<?php
/**
 * Member Friend Histories (member-friend-history)
 * @var $this app\components\View
 * @var $this ommu\member\controllers\history\FriendController
 * @var $model ommu\member\models\MemberFriendHistory
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 31 October 2018, 05:45 WIB
 * @link https://github.com/ommu/mod-member
 *
 */

use yii\helpers\Url;
use yii\widgets\DetailView;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Friend Histories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->friend->request->displayname;

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id'=>$model->id]), 'htmlOptions' => ['data-confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method'=>'post', 'class'=>'btn btn-danger'], 'icon' => 'trash'],
];
?>

<div class="member-friend-history-view">

<?php echo DetailView::widget([
	'model' => $model,
	'options' => [
		'class'=>'table table-striped detail-view',
	],
	'attributes' => [
		'id',
		[
			'attribute' => 'type_id',
			'value' => isset($model->type) ? $model->type->title->message : '-',
		],
		[
			'attribute' => 'st_user_search',
			'value' => isset($model->friend) ? $model->friend->user->displayname : '-',
		],
		[
			'attribute' => 'nd_user_search',
			'value' => isset($model->friend) ? $model->friend->request->displayname : '-',
		],
		[
			'attribute' => 'creation_date',
			'value' => Yii::$app->formatter->asDatetime($model->creation_date, 'medium'),
		],
		[
			'attribute' => 'creation_search',
			'value' => isset($model->creation) ? $model->creation->displayname : '-',
		],
	],
]) ?>

</div>