<?php
/**
 * Member Userlevels (member-userlevel)
 * @var $this app\components\View
 * @var $this ommu\member\controllers\setting\UserLevelController
 * @var $model ommu\member\models\MemberUserlevel
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 OMMU (www.ommu.id)
 * @created date 2 October 2018, 09:25 WIB
 * @modified date 27 October 2018, 22:28 WIB
 * @link https://github.com/ommu/mod-member
 *
 */

use yii\helpers\Url;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Userlevels'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Create');
?>

<div class="member-userlevel-create">

<?php echo $this->render('_form', [
	'model' => $model,
]); ?>

</div>