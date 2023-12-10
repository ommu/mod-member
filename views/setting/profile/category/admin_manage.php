<?php
/**
 * Member Profile Categories (member-profile-category)
 * @var $this app\components\View
 * @var $this ommu\member\controllers\setting\profile\CategoryController
 * @var $model ommu\member\models\MemberProfileCategory
 * @var $searchModel ommu\member\models\search\MemberProfileCategory
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 OMMU (www.ommu.id)
 * @created date 2 October 2018, 09:58 WIB
 * @modified date 2 September 2019, 18:28 WIB
 * @link https://github.com/ommu/mod-member
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\grid\GridView;
use yii\widgets\Pjax;

$this->params['breadcrumbs'][] = $this->title;

if ($profile != null) {
	$this->params['menu']['content'] = [
		['label' => Yii::t('app', 'Add Profile Category'), 'url' => Url::to(['create', 'id' => $profile->profile_id]), 'icon' => 'plus-square', 'htmlOptions' => ['class' => 'btn btn-success modal-btn']],
	];
}
$this->params['menu']['option'] = [
	//['label' => Yii::t('app', 'Search'), 'url' => 'javascript:void(0);'],
	['label' => Yii::t('app', 'Grid Option'), 'url' => 'javascript:void(0);'],
];
?>

<div class="member-profile-category-manage">
<?php Pjax::begin(); ?>

<?php if ($profile != null) {
    echo $this->render('/setting/profile/admin/admin_view', ['model' => $profile, 'small' => true]);
} ?>

<?php //echo $this->render('_search', ['model' => $searchModel]); ?>

<?php echo $this->render('_option_form', ['model' => $searchModel, 'gridColumns' => $searchModel->activeDefaultColumns($columns), 'route' => $this->context->route]); ?>

<?php
$columnData = $columns;
array_push($columnData, [
	'class' => 'app\components\grid\ActionColumn',
	'header' => Yii::t('app', 'Option'),
	'urlCreator' => function($action, $model, $key, $index) {
        if ($action == 'view') {
            return Url::to(['view', 'id' => $key]);
        }
        if ($action == 'update') {
            return Url::to(['update', 'id' => $key]);
        }
        if ($action == 'delete') {
            return Url::to(['delete', 'id' => $key]);
        }
	},
	'buttons' => [
		'view' => function ($url, $model, $key) {
			return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, ['title' => Yii::t('app', 'Detail Profile Category'), 'class' => 'modal-btn']);
		},
		'update' => function ($url, $model, $key) {
			return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, ['title' => Yii::t('app', 'Update Profile Category'), 'class' => 'modal-btn']);
		},
		'delete' => function ($url, $model, $key) {
			return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
				'title' => Yii::t('app', 'Delete Profile Category'),
				'data-confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
				'data-method'  => 'post',
			]);
		},
	],
	'template' => '{view} {update} {delete}',
]);

echo GridView::widget([
	'dataProvider' => $dataProvider,
	'filterModel' => $searchModel,
	'columns' => $columnData,
]); ?>

<?php Pjax::end(); ?>
</div>