<?php
/**
 * Member Settings (member-setting)
 * @var $this app\components\View
 * @var $this ommu\member\controllers\setting\AdminController
 * @var $model ommu\member\models\MemberSetting
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 5 November 2018, 06:17 WIB
 * @modified date 5 November 2018, 06:17 WIB
 * @modified by Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @link https://github.com/ommu/mod-member
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

$this->params['breadcrumbs'][] = $this->title;
?>

<div class="member-setting-view">

<?php echo DetailView::widget([
	'model' => $model,
	'options' => [
		'class'=>'table table-striped detail-view',
	],
	'attributes' => [
		'id',
		'license',
		[
			'attribute' => 'permission',
			'value' => $model::getPermission($model->permission),
		],
		[
			'attribute' => 'meta_description',
			'value' => $model->meta_description ? $model->meta_description : '-',
		],
		[
			'attribute' => 'meta_keyword',
			'value' => $model->meta_keyword ? $model->meta_keyword : '-',
		],
		[
			'attribute' => 'form_custom_insert_field',
			'value' => serialize($model->form_custom_insert_field),
		],
		'level_member_default',
		'profile_user_limit',
		[
			'attribute' => 'profile_page_user_auto_follow',
			'value' => $this->filterYesNo($model->profile_page_user_auto_follow),
		],
		[
			'attribute' => 'profile_views',
			'value' => serialize($model->profile_views),
		],
		'photo_limit',
		[
			'attribute' => 'photo_resize',
			'value' => $this->filterYesNo($model->photo_resize),
		],
		[
			'attribute' => 'photo_resize_size',
			'value' => $model::getSize($model->photo_resize_size),
		],
		[
			'attribute' => 'photo_view_size',
			'value' => $model::getSize($model->photo_view_size),
		],
		[
			'attribute' => 'photo_header_view_size',
			'value' => $model::getSize($model->photo_header_view_size),
		],
		[
			'attribute' => 'photo_file_type',
			'value' => $model->photo_file_type,
		],
		[
			'attribute' => 'friends_auto_follow',
			'value' => $this->filterYesNo($model->friends_auto_follow),
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
			'attribute' => '',
			'value' => Html::a(Yii::t('app', 'Update'), Url::to(['update']), [
				'class' => 'btn btn-primary',
			]),
			'format' => 'raw',
		],
	],
]) ?>

</div>