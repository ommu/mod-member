<?php
/**
 * Member Friend Types (member-friend-type)
 * @var $this yii\web\View
 * @var $this ommu\member\controllers\setting\FriendTypeController
 * @var $model ommu\member\models\MemberFriendType
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 27 October 2018, 23:10 WIB
 * @link https://github.com/ommu/mod-member
 *
 */

use yii\helpers\Url;
use yii\widgets\DetailView;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Friend Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->title->message;

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Update'), 'url' => Url::to(['update', 'id'=>$model->id]), 'htmlOptions' => ['class'=>'modal-btn'], 'icon' => 'pencil', 'htmlOptions' => ['class'=>'btn btn-primary btn-sm']],
	['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id'=>$model->id]), 'htmlOptions' => ['data-confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method'=>'post', 'class'=>'btn btn-danger btn-sm'], 'icon' => 'trash'],
];
?>

<div class="member-friend-type-view">

<?php echo DetailView::widget([
	'model' => $model,
	'options' => [
		'class'=>'table table-striped detail-view',
	],
	'attributes' => [
		'id',
		[
			'attribute' => 'publish',
			'value' => $this->quickAction(Url::to(['publish', 'id'=>$model->primaryKey]), $model->publish),
			'format' => 'raw',
		],
		[
			'attribute' => 'default',
			'value' => $this->filterYesNo($model->default),
		],
		[
			'attribute' => 'type_name_i',
			'value' => $model->type_name_i,
		],
		[
			'attribute' => 'type_desc_i',
			'value' => $model->type_desc_i,
		],
		[
			'attribute' => 'creation_date',
			'value' => Yii::$app->formatter->asDatetime($model->creation_date, 'medium'),
		],
		[
			'attribute' => 'creation_search',
			'value' => isset($model->creation) ? $model->creation->displayname : '-',
		],
		[
			'attribute' => 'modified_date',
			'value' => Yii::$app->formatter->asDatetime($model->modified_date, 'medium'),
		],
		[
			'attribute' => 'modified_search',
			'value' => isset($model->modified) ? $model->modified->displayname : '-',
		],
		[
			'attribute' => 'updated_date',
			'value' => Yii::$app->formatter->asDatetime($model->updated_date, 'medium'),
		],
	],
]) ?>

</div>