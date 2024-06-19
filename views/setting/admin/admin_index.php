<?php
/**
 * Member Settings (member-setting)
 * @var $this app\components\View
 * @var $this ommu\member\controllers\setting\AdminController
 * @var $model ommu\member\models\MemberSetting
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2018 OMMU (www.ommu.id)
 * @created date 5 November 2018, 06:17 WIB
 * @link https://github.com/ommu/mod-member
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;

$this->params['breadcrumbs'][] = $this->title;
?>

<?php echo $this->renderWidget('/setting/profile/admin/admin_manage', [
	'contentMenu' => true,
	'searchModel' => $searchModel,
	'dataProvider' => $dataProvider,
	'columns' => $columns,
]); ?>

<?php echo $this->renderWidget(!$model->isNewRecord ? 'admin_view' : 'admin_update', [
	'contentMenu' => true,
	'model' => $model,
]); ?>