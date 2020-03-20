<?php
/**
 * Member Document Types (member-document-type)
 * @var $this app\components\View
 * @var $this ommu\member\controllers\setting\DocumentController
 * @var $model ommu\member\models\MemberDocumentType
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 OMMU (www.ommu.id)
 * @created date 2 October 2018, 11:07 WIB
 * @modified date 27 October 2018, 22:44 WIB
 * @link https://github.com/ommu/mod-member
 *
 */

use yii\helpers\Url;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Document Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Create');
?>

<div class="member-document-type-create">

<?php echo $this->render('_form', [
	'model' => $model,
]); ?>

</div>