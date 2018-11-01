<?php
/**
 * Member Companies (member-company)
 * @var $this yii\web\View
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

use Yii;
use yii\helpers\Url;
use yii\widgets\DetailView;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Companies'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->member->displayname;

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Back To Manage'), 'url' => Url::to(['index']), 'icon' => 'table'],
	['label' => Yii::t('app', 'Update'), 'url' => Url::to(['update', 'id' => $model->id]), 'icon' => 'pencil'],
	['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id' => $model->id]), 'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'), 'method' => 'post', 'icon' => 'trash'],
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
			'attribute' => 'publish',
			'value' => $this->quickAction(Url::to(['publish', 'id'=>$model->primaryKey]), $model->publish),
			'format' => 'raw',
		],
		[
			'attribute' => 'member_search',
			'value' => isset($model->member) ? $model->member->displayname : '-',
		],
		[
			'attribute' => 'company_search',
			'value' => isset($model->company) ? $model->company->directory->directory_name : '-',
		],
		[
			'attribute' => 'company_type_id',
			'value' => isset($model->companyType) ? $model->companyType->title->message : '-',
		],
		[
			'attribute' => 'info_intro',
			'value' => $model->info_intro ? $model->info_intro : '-',
		],
		[
			'attribute' => 'info_article',
			'value' => $model->info_article ? $model->info_article : '-',
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
			'value' => !in_array($model->creation_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00']) ? Yii::$app->formatter->format($model->creation_date, 'datetime') : '-',
		],
		[
			'attribute' => 'creation_search',
			'value' => isset($model->creation) ? $model->creation->displayname : '-',
		],
		[
			'attribute' => 'modified_date',
			'value' => !in_array($model->modified_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00']) ? Yii::$app->formatter->format($model->modified_date, 'datetime') : '-',
		],
		[
			'attribute' => 'modified_search',
			'value' => isset($model->modified) ? $model->modified->displayname : '-',
		],
		[
			'attribute' => 'updated_date',
			'value' => !in_array($model->updated_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00']) ? Yii::$app->formatter->format($model->updated_date, 'datetime') : '-',
		],
	],
]) ?>

</div>
