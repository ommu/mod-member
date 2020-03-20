<?php
/**
 * Members (members)
 * @var $this app\components\View
 * @var $this ommu\member\controllers\manage\AdminController
 * @var $model ommu\member\models\Members
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 OMMU (www.ommu.id)
 * @created date 30 October 2018, 22:51 WIB
 * @modified date 4 November 2018, 17:03 WIB
 * @link https://github.com/ommu/mod-member
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use ommu\member\models\Members;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Members'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->displayname;

if(!$small) {
$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Update'), 'url' => Url::to(['update', 'id'=>$model->member_id]), 'icon' => 'pencil', 'htmlOptions' => ['class'=>'btn btn-primary']],
	['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id'=>$model->member_id]), 'htmlOptions' => ['data-confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method'=>'post', 'class'=>'btn btn-danger'], 'icon' => 'trash'],
];
} ?>

<div class="members-view">

<?php
$attributes = [
	[
		'attribute' => 'member_id',
		'value' => $model->member_id,
		'visible' => !$small,
	],
	[
		'attribute' => 'publish',
		'value' => $model->quickAction(Url::to(['publish', 'id'=>$model->primaryKey]), $model->publish, 'Enable,Disable'),
		'format' => 'raw',
		'visible' => !$small,
	],
	[
		'attribute' => 'approved',
		'value' => $model->filterYesNo($model->approved),
	],
	[
		'attribute' => 'profile_id',
		'value' => isset($model->profile) ? $model->profile->title->message : '-',
	],
	[
		'attribute' => 'member_private',
		'value' => Members::getMemberPrivate($model->member_private),
	],
	[
		'attribute' => 'username',
		'value' => $model->username ? $model->username : '-',
	],
	[
		'attribute' => 'displayname',
		'value' => $model->displayname ? $model->displayname : '-',
	],
	[
		'attribute' => 'photo_header',
		'value' => function ($model) {
			$uploadPath = join('/', [Members::getUploadPath(false), $model->member_id]);
			return $model->photo_header ? Html::img(Url::to(join('/', ['@webpublic', $uploadPath, $model->photo_header])), ['alt'=>$model->photo_header, 'class'=>'d-block border border-width-3 mb-3']).$model->photo_header : '-';
		},
		'format' => 'raw',
	],
	[
		'attribute' => 'photo_profile',
		'value' => function ($model) {
			$uploadPath = join('/', [Members::getUploadPath(false), $model->member_id]);
			return $model->photo_profile ? Html::img(Url::to(join('/', ['@webpublic', $uploadPath, $model->photo_profile])), ['alt'=>$model->photo_profile, 'class'=>'d-block border border-width-3 mb-3']).$model->photo_profile : '-';
		},
		'format' => 'raw',
	],
	[
		'attribute' => 'short_biography',
		'value' => $model->short_biography ? $model->short_biography : '-',
	],
	[
		'attribute' => 'approved_date',
		'value' => Yii::$app->formatter->asDatetime($model->approved_date, 'medium'),
	],
	[
		'attribute' => 'approved_search',
		'value' => isset($model->approvedRltn) ? $model->approvedRltn->displayname : '-',
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
	[
		'attribute' => 'modified_date',
		'value' => Yii::$app->formatter->asDatetime($model->modified_date, 'medium'),
		'visible' => !$small,
	],
	[
		'attribute' => 'modifiedDisplayname',
		'value' => isset($model->modified) ? $model->modified->displayname : '-',
	],
	[
		'attribute' => 'updated_date',
		'value' => Yii::$app->formatter->asDatetime($model->updated_date, 'medium'),
		'visible' => !$small,
	],
	[
		'attribute' => '',
		'value' => Html::a(Yii::t('app', 'Update'), ['update', 'id'=>$model->primaryKey], ['title'=>Yii::t('app', 'Update'), 'class'=>'btn btn-success btn-sm']),
		'format' => 'html',
		'visible' => !$small && Yii::$app->request->isAjax ? true : false,
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