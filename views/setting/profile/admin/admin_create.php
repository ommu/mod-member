<?php
/**
 * Member Profiles (member-profile)
 * @var $this app\components\View
 * @var $this ommu\member\controllers\setting\profile\AdminController
 * @var $model ommu\member\models\MemberProfile
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2018 OMMU (www.ommu.id)
 * @created date 2 October 2018, 09:48 WIB
 * @modified date 2 September 2019, 18:27 WIB
 * @link https://github.com/ommu/mod-member
 *
 */

use yii\helpers\Url;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Profiles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Create');
?>

<div class="member-profile-create">

<?php echo $this->render('_form', [
	'model' => $model,
]); ?>

</div>
