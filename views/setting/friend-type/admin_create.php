<?php
/**
 * Member Friend Types (member-friend-type)
 * @var $this yii\web\View
 * @var $this ommu\member\controllers\setting\FriendTypeController
 * @var $model ommu\member\models\MemberFriendType
 * @var $form yii\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 27 October 2018, 23:10 WIB
 * @link https://github.com/ommu/mod-member
 *
 */

use yii\helpers\Url;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Friend Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Create');
?>

<div class="member-friend-type-create">

<?php echo $this->render('_form', [
	'model' => $model,
]); ?>

</div>