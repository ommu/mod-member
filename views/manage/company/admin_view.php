<?php
/**
 * Member Companies (member-company)
 * @var $this app\components\View
 * @var $this ommu\member\controllers\manage\CompanyController
 * @var $model ommu\member\models\MemberCompany
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 31 October 2018, 15:48 WIB
 * @link https://github.com/ommu/mod-member
 *
 */

use yii\helpers\Url;
use yii\widgets\DetailView;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Companies'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->member->displayname;

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Update'), 'url' => Url::to(['update', 'id'=>$model->id]), 'icon' => 'pencil', 'htmlOptions' => ['class'=>'btn btn-primary']],
	['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id'=>$model->id]), 'htmlOptions' => ['data-confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method'=>'post', 'class'=>'btn btn-danger'], 'icon' => 'trash'],
];
?>

<div class="member-company-view">

<?php echo DetailView::widget([
	'model' => $model,
	'options' => [
		'class'=>'table table-striped detail-view',
	],
	'attributes' => [
		'id',
		[
			'attribute' => 'member_i',
			'value' => $model->member_i,
		],
		[
			'attribute' => 'company_type_id',
			'value' => isset($model->companyType) ? $model->companyType->title->message : '-',
		],
		[
			'attribute' => 'company_cat_id',
			'value' => isset($model->companyCategory) ? $model->companyCategory->title->message : '-',
		],
		[
			'attribute' => 'info_intro',
			'value' => $model->info_intro ? $model->info_intro : '-',
		],
		[
			'attribute' => 'info_article',
			'value' => $model->info_article ? $model->info_article : '-',
			'format' => 'raw',
		],
		[
			'attribute' => 'company_address',
			'value' => $model->company_address ? $model->company_address : '-',
		],
		'company_country_id',
		'company_province_id',
		'company_city_id',
		'company_district',
		'company_village',
		'company_zipcode',
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