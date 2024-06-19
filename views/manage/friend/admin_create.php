<?php
/**
 * Member Friends (member-friends)
 * @var $this app\components\View
 * @var $this ommu\member\controllers\manage\FriendController
 * @var $model ommu\member\models\MemberFriends
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2018 OMMU (www.ommu.id)
 * @created date 31 October 2018, 13:53 WIB
 * @link https://github.com/ommu/mod-member
 *
 */

use yii\helpers\Url;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Friends'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Create');
?>

<div class="member-friends-create">

<?php echo $this->render('_form', [
	'model' => $model,
]); ?>

</div>
