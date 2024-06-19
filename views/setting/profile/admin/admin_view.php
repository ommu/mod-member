<?php
/**
 * Member Profiles (member-profile)
 * @var $this app\components\View
 * @var $this ommu\member\controllers\setting\profile\AdminController
 * @var $model ommu\member\models\MemberProfile
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2018 OMMU (www.ommu.id)
 * @created date 2 October 2018, 09:48 WIB
 * @modified date 2 September 2019, 18:27 WIB
 * @link https://github.com/ommu/mod-member
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Profiles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->title->message;
?>

<div class="member-profile-view">

<?php
$attributes = [
	[
		'attribute' => 'profile_id',
		'value' => $model->profile_id ? $model->profile_id : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'publish',
		'value' => $model->quickAction(Url::to(['publish', 'id' => $model->primaryKey]), $model->publish, 'Enable,Disable'),
		'format' => 'raw',
		'visible' => !$small,
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
		'value' => $model::parseAssignmentRoles($model->assignment_roles),
		'format' => 'html',
		'visible' => !$small,
	],
	[
		'attribute' => 'profile_personal',
		'value' => $model->filterYesNo($model->profile_personal),
	],
	[
		'attribute' => 'multiple_user',
		'value' => $model->filterYesNo($model->multiple_user),
		'visible' => !$small,
	],
	[
		'attribute' => 'user_limit',
		'value' => $model->user_limit ? $model->user_limit : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'categories',
		'value' => function ($model) {
			$categories = $model->getCategories(true);
			return Html::a($categories, ['setting/profile/category/manage', 'profile' => $model->primaryKey, 'publish' => 1], ['title' => Yii::t('app', '{count} categories', ['count' => $categories])]);
		},
		'format' => 'html',
		'visible' => !$small,
	],
	[
		'attribute' => 'documents',
		'value' => function ($model) {
			$documents = $model->getDocuments(true);
			return Html::a($documents, ['setting/profile/document/manage', 'profile' => $model->primaryKey, 'publish' => 1], ['title' => Yii::t('app', '{count} documents', ['count' => $documents])]);
		},
		'format' => 'html',
		'visible' => !$small,
	],
	[
		'attribute' => 'members',
		'value' => function ($model) {
			$members = $model->getMembers(true);
			return Html::a($members, ['admin/manage', 'profile' => $model->primaryKey, 'publish' => 1], ['title' => Yii::t('app', '{count} members', ['count' => $members])]);
		},
		'format' => 'html',
		'visible' => !$small,
	],
	[
		'attribute' => 'creation_date',
		'value' => Yii::$app->formatter->asDatetime($model->creation_date, 'medium'),
		'visible' => !$small,
	],
	[
		'attribute' => 'creationDisplayname',
		'value' => isset($model->creation) ? $model->creation->displayname : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'modified_date',
		'value' => Yii::$app->formatter->asDatetime($model->modified_date, 'medium'),
		'visible' => !$small,
	],
	[
		'attribute' => 'modifiedDisplayname',
		'value' => isset($model->modified) ? $model->modified->displayname : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'updated_date',
		'value' => Yii::$app->formatter->asDatetime($model->updated_date, 'medium'),
		'visible' => !$small,
	],
	[
		'attribute' => '',
		'value' => Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->primaryKey], ['title' => Yii::t('app', 'Update'), 'class' => 'btn btn-primary btn-sm']),
		'format' => 'html',
		'visible' => !$small && Yii::$app->request->isAjax ? true : false,
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