<?php
/**
 * Member Company Types (member-company-type)
 * @var $this yii\web\View
 * @var $this ommu\member\controllers\setting\CompanyTypeController
 * @var $model ommu\member\models\MemberCompanyType
 * @var $form yii\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 27 October 2018, 22:58 WIB
 * @link https://github.com/ommu/mod-member
 *
 */

use yii\helpers\Url;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Company Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title->message, 'url' => ['view', 'id'=>$model->type_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Back To Manage'), 'url' => Url::to(['index']), 'icon' => 'table'],
	['label' => Yii::t('app', 'Detail'), 'url' => Url::to(['view', 'id'=>$model->type_id]), 'icon' => 'eye'],
	['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id'=>$model->type_id]), 'confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'), 'method'=>'post', 'icon' => 'trash'],
];
?>

<div class="member-company-type-update">

<?php echo $this->render('_form', [
	'model' => $model,
]); ?>

</div>