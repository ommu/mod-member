<?php
/**
 * Member Categories (member-categories)
 * @var $this yii\web\View
 * @var $this ommu\member\controllers\manage\CategoryController
 * @var $model ommu\member\models\MemberCategories
 * @var $form yii\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 1 November 2018, 01:09 WIB
 * @link https://github.com/ommu/mod-member
 *
 */

use Yii;
use yii\helpers\Url;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->member->displayname, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Back To Manage'), 'url' => Url::to(['index']), 'icon' => 'table'],
	['label' => Yii::t('app', 'Detail'), 'url' => Url::to(['view', 'id' => $model->id]), 'icon' => 'eye'],
	['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id' => $model->id]), 'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'), 'method' => 'post', 'icon' => 'trash'],
];
?>

<div class="member-categories-update">

<?php echo $this->render('_form', [
	'model' => $model,
]); ?>

</div>