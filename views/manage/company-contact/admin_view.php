<?php
/**
 * Member Company Contacts (member-company-contact)
 * @var $this yii\web\View
 * @var $this ommu\member\controllers\manage\CompanyContactController
 * @var $model ommu\member\models\MemberCompanyContact
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 1 November 2018, 19:49 WIB
 * @link https://github.com/ommu/mod-member
 *
 */

use yii\helpers\Url;
use yii\widgets\DetailView;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Company Contacts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->memberCompany->member->displayname;

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Back To Manage'), 'url' => Url::to(['index', 'company'=>$model->member_company_id]), 'icon' => 'table'],
	['label' => Yii::t('app', 'Update'), 'url' => Url::to(['update', 'id'=>$model->id]), 'htmlOptions' => ['class'=>'modal-btn'], 'icon' => 'pencil', 'htmlOptions' => ['class'=>'btn btn-primary btn-sm']],
	['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id'=>$model->id]), 'htmlOptions' => ['data-confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method'=>'post', 'class'=>'btn btn-danger btn-sm'], 'icon' => 'trash'],
];
?>

<div class="member-company-contact-view">

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
			'value' => $this->quickAction(Url::to(['status', 'id'=>$model->primaryKey]), $model->status, 'Verified,Unverified'),
			'format' => 'raw',
		],
		[
			'attribute' => 'profile_search',
			'value' => isset($model->member) ? $model->memberCompany->member->profile->title->message : '-',
		],
		[
			'attribute' => 'member_search',
			'value' => isset($model->member) ? $model->memberCompany->member->displayname : '-',
		],
		[
			'attribute' => 'contact_cat_id',
			'value' => isset($model->category) ? $model->category->title->message : '-',
		],
		[
			'attribute' => 'contact_value',
			'value' => $model->contact_value ? $model->contact_value : '-',
		],
		[
			'attribute' => 'verified_date',
			'value' => Yii::$app->formatter->asDatetime($model->verified_date, 'medium'),
		],
		[
			'attribute' => 'verified_search',
			'value' => isset($model->verified) ? $model->verified->displayname : '-',
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