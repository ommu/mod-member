<?php
/**
 * Member Companies (member-company)
 * @var $this app\components\View
 * @var $this ommu\member\controllers\manage\CompanyController
 * @var $model ommu\member\models\MemberCompany
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 OMMU (www.ommu.id)
 * @created date 31 October 2018, 15:48 WIB
 * @link https://github.com/ommu/mod-member
 *
 */

use yii\helpers\Url;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Companies'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Create');
?>

<div class="member-company-create">

<?php echo $this->render('_form', [
	'model' => $model,
	'member' => $member,
]); ?>

</div>
