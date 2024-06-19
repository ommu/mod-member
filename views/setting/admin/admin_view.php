<?php
/**
 * Member Settings (member-setting)
 * @var $this app\components\View
 * @var $this ommu\member\controllers\setting\AdminController
 * @var $model ommu\member\models\MemberSetting
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2018 OMMU (www.ommu.id)
 * @created date 5 November 2018, 06:17 WIB
 * @modified date 3 September 2019, 10:42 WIB
 * @link https://github.com/ommu/mod-member
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

$this->params['breadcrumbs'][] = $this->title;

if (!$small) {
    $this->params['menu']['content'] = [
        ['label' => Yii::t('app', 'Reset'), 'url' => Url::to(['delete']), 'htmlOptions' => ['data-confirm' => Yii::t('app', 'Are you sure you want to reset this setting?'), 'data-method' => 'post', 'class' => 'btn btn-danger'], 'icon' => 'trash'],
    ];
} ?>

<div class="member-setting-view">

<?php
$attributes = [
	[
		'attribute' => 'id',
		'value' => $model->id ? $model->id : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'license',
		'value' => $model->license ? $model->license : '-',
	],
	[
		'attribute' => 'permission',
		'value' => $model::getPermission($model->permission),
	],
	[
		'attribute' => 'meta_description',
		'value' => $model->meta_description ? $model->meta_description : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'meta_keyword',
		'value' => $model->meta_keyword ? $model->meta_keyword : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'personal_profile_id',
		'value' => isset($model->personal) ? $model->personal->profile_name_i : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'company_profile_id',
		'value' => isset($model->company) ? $model->company->profile_name_i : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'group_profile_id',
		'value' => isset($model->group) ? $model->group->profile_name_i : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'profile_user_limit',
		'value' => $model->profile_user_limit ? $model->profile_user_limit : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'profile_page_user_auto_follow',
		'value' => $model->filterYesNo($model->profile_page_user_auto_follow),
		'visible' => !$small,
	],
	[
		'attribute' => 'friends_auto_follow',
		'value' => $model->filterYesNo($model->friends_auto_follow),
		'visible' => !$small,
	],
	[
		'attribute' => 'photo_limit',
		'value' => $model->photo_limit ? $model->photo_limit : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'photo_resize',
		'value' => $model::getPhotoResize($model->photo_resize),
		'visible' => !$small,
	],
	[
		'attribute' => 'photo_resize_size',
		'value' => $model::getSize($model->photo_resize_size),
		'visible' => !$small,
	],
	[
		'attribute' => 'photo_view_size',
		'value' => $model::parsePhotoViewSize($model->photo_view_size),
		'format' => 'html',
		'visible' => !$small,
	],
	[
		'attribute' => 'photo_header_view_size',
		'value' => $model::getSize($model->photo_header_view_size),
		'visible' => !$small,
	],
	[
		'attribute' => 'photo_file_type',
		'value' => $model->photo_file_type,
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
		'attribute' => '',
		'value' => Html::a(Yii::t('app', 'Update'), Url::to(['update']), ['class' => 'btn btn-primary']),
		'format' => 'html',
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