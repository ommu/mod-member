<?php
/**
 * Member Settings (member-setting)
 * @var $this app\components\View
 * @var $this ommu\member\controllers\setting\AdminController
 * @var $model ommu\member\models\MemberSetting
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 OMMU (www.ommu.id)
 * @created date 5 November 2018, 06:17 WIB
 * @modified date 3 September 2019, 10:42 WIB
 * @link https://github.com/ommu/mod-member
 *
 */

use yii\helpers\Url;

$this->params['breadcrumbs'][] = $this->title;
?>

<div class="member-setting-update">

<?php echo $this->render('_form', [
	'model' => $model,
]); ?>

</div>