<?php
/**
 * Member Profile Categories (member-profile-category)
 * @var $this app\components\View
 * @var $this ommu\member\controllers\setting\ProfileCategoryController
 * @var $model ommu\member\models\MemberProfileCategory
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 2 October 2018, 09:58 WIB
 * @modified date 28 October 2018, 21:38 WIB
 * @link https://github.com/ommu/mod-member
 *
 */

use yii\helpers\Url;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Profile Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Create');

$profile = Yii::$app->request->get('profile');
$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Back To Manage'), 'url' => Url::to(['index', 'profile'=>$profile]), 'icon' => 'table'],
];
?>

<div class="member-profile-category-create">

<?php echo $this->render('_form', [
	'model' => $model,
	'profile' => $profile,
]); ?>

</div>
