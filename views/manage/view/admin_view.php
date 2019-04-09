<?php
/**
 * Member Views (member-views)
 * @var $this yii\web\View
 * @var $this ommu\member\controllers\manage\ViewController
 * @var $model ommu\member\models\MemberViews
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 31 October 2018, 12:36 WIB
 * @link https://github.com/ommu/mod-member
 *
 */

use yii\helpers\Url;
use yii\widgets\DetailView;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Views'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->member->displayname;

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Back To Manage'), 'url' => Url::to(['index', 'member'=>$model->member_id]), 'icon' => 'table'],
	['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id'=>$model->view_id]), 'htmlOptions' => ['data-confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method'=>'post', 'class'=>'btn btn-danger btn-sm'], 'icon' => 'trash'],
];
?>

<div class="member-views-view">

<?php echo DetailView::widget([
	'model' => $model,
	'options' => [
		'class'=>'table table-striped detail-view',
	],
	'attributes' => [
		'view_id',
		[
			'attribute' => 'publish',
			'value' => $this->quickAction(Url::to(['publish', 'id'=>$model->primaryKey]), $model->publish),
			'format' => 'raw',
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
			'attribute' => 'user_search',
			'value' => isset($model->user) ? $model->user->displayname : '-',
		],
		'views',
		[
			'attribute' => 'view_date',
			'value' => Yii::$app->formatter->asDatetime($model->view_date, 'medium'),
		],
		'view_ip',
		[
			'attribute' => 'modified_date',
			'value' => Yii::$app->formatter->asDatetime($model->modified_date, 'medium'),
		],
		[
			'attribute' => 'modified_search',
			'value' => isset($model->modified) ? $model->modified->displayname : '-',
		],
		[
			'attribute' => 'deleted_date',
			'value' => Yii::$app->formatter->asDatetime($model->deleted_date, 'medium'),
		],
	],
]) ?>

</div>