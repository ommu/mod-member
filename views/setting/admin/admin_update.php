<?php
/**
 * Member Settings (member-setting)
 * @var $this yii\web\View
 * @var $this ommu\member\controllers\setting\AdminController
 * @var $model ommu\member\models\MemberSetting
 * @var $form yii\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 5 November 2018, 06:17 WIB
 * @modified date 5 November 2018, 06:17 WIB
 * @modified by Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
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