<?php
/**
 * Member Contact Categories (member-contact-category)
 * @var $this app\components\View
 * @var $this ommu\member\controllers\setting\ContactCategoryController
 * @var $model ommu\member\models\MemberContactCategory
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 OMMU (www.ommu.id)
 * @created date 4 October 2018, 14:36 WIB
 * @modified date 27 October 2018, 22:50 WIB
 * @link https://github.com/ommu/mod-member
 *
 */

use yii\helpers\Url;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Contact Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Create');
?>

<div class="member-contact-category-create">

<?php echo $this->render('_form', [
	'model' => $model,
]); ?>

</div>