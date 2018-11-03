<?php
/**
 * Member Profile Categories (member-profile-category)
 * @var $this yii\web\View
 * @var $this ommu\member\controllers\setting\ProfileCategoryController
 * @var $model ommu\member\models\MemberProfileCategory
 * @var $form yii\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 2 October 2018, 09:58 WIB
 * @modified date 28 October 2018, 21:38 WIB
 * @link https://github.com/ommu/mod-member
 *
 */

use Yii;
use yii\helpers\Url;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Profile Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title->message, 'url' => ['view', 'id'=>$model->cat_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Back To Manage'), 'url' => Url::to(['index', 'profile'=>$model->profile_id]), 'icon' => 'table'],
	['label' => Yii::t('app', 'Detail'), 'url' => Url::to(['view', 'id'=>$model->cat_id]), 'icon' => 'eye'],
	['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id'=>$model->cat_id]), 'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'), 'method' => 'post', 'icon' => 'trash'],
];
?>

<div class="member-profile-category-update">

<?php echo $this->render('_form', [
	'model' => $model,
	'profile' => $profile,
]); ?>

</div>