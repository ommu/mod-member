<?php
/**
 * Member Documents (member-documents)
 * @var $this app\components\View
 * @var $this ommu\member\controllers\manage\DocumentController
 * @var $model ommu\member\models\MemberDocuments
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 OMMU (www.ommu.id)
 * @created date 1 November 2018, 09:12 WIB
 * @link https://github.com/ommu/mod-member
 *
 */

use yii\helpers\Url;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Documents'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Create');

$member = Yii::$app->request->get('member');
$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Back To Manage'), 'url' => Url::to(['index', 'member'=>$member]), 'icon' => 'table'],
];
?>

<div class="member-documents-create">

<?php echo $this->render('_form', [
	'model' => $model,
	'document' => $document,
]); ?>

</div>
