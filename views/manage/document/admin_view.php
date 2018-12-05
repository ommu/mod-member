<?php
/**
 * Member Documents (member-documents)
 * @var $this yii\web\View
 * @var $this ommu\member\controllers\manage\DocumentController
 * @var $model ommu\member\models\MemberDocuments
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 1 November 2018, 09:12 WIB
 * @link https://github.com/ommu/mod-member
 *
 */

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\DetailView;
use ommu\member\models\MemberDocuments;
use ommu\member\models\Members;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Documents'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->document_filename;

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Back To Manage'), 'url' => Url::to(['index', 'member'=>$model->member_id]), 'icon' => 'table'],
	['label' => Yii::t('app', 'Update'), 'url' => Url::to(['update', 'id'=>$model->id]), 'htmlOptions' => ['class'=>'modal-btn'], 'icon' => 'pencil'],
	['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id'=>$model->id]), 'htmlOptions' => ['data-confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method'=>'post'], 'icon' => 'trash'],
];
?>

<div class="member-documents-view">

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
			'attribute' => 'status',
			'value' => MemberDocuments::getStatus($model->status);
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
			'attribute' => 'document_search',
			'value' => isset($model->profileDocument) ? $model->profileDocument->document->title->message : '-',
		],
		[
			'attribute' => 'document_filename',
			'value' => function ($model) {
				$uploadPath = join('/', [Members::getUploadPath(false), $model->member_id]);
				return $model->document_filename ? Html::img(join('/', [Url::Base(), $uploadPath, $model->document_filename]), ['width' => '100%']).'<br/><br/>'.$model->document_filename : '-';
			},
			'format' => 'raw',
		],
		[
			'attribute' => 'statuses_date',
			'value' => Yii::$app->formatter->asDatetime($model->statuses_date, 'medium'),
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