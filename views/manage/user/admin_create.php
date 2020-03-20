<?php
/**
 * Member Users (member-user)
 * @var $this app\components\View
 * @var $this ommu\member\controllers\manage\UserController
 * @var $model ommu\member\models\MemberUser
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 OMMU (www.ommu.id)
 * @created date 31 October 2018, 13:38 WIB
 * @link https://github.com/ommu/mod-member
 *
 */

use yii\helpers\Url;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Create');

$member = Yii::$app->request->get('member');
$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Back To Manage'), 'url' => Url::to(['index', 'member'=>$member]), 'icon' => 'table'],
];
?>

<div class="member-user-create">

<?php echo $this->render('_form', [
	'model' => $model,
]); ?>

</div>
