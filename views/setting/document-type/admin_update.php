<?php
/**
 * Member Document Types (member-document-type)
 * @var $this yii\web\View
 * @var $this ommu\member\controllers\setting\DocumentTypeController
 * @var $model ommu\member\models\MemberDocumentType
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 2 October 2018, 11:07 WIB
 * @modified date 27 October 2018, 22:44 WIB
 * @link https://github.com/ommu/mod-member
 *
 */

use yii\helpers\Url;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Document Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title->message, 'url' => ['view', 'id'=>$model->document_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Detail'), 'url' => Url::to(['view', 'id'=>$model->document_id]), 'icon' => 'eye', 'htmlOptions' => ['class'=>'btn btn-success']],
	['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id'=>$model->document_id]), 'htmlOptions' => ['data-confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method'=>'post', 'class'=>'btn btn-danger'], 'icon' => 'trash'],
];
?>

<div class="member-document-type-update">

<?php echo $this->render('_form', [
	'model' => $model,
]); ?>

</div>