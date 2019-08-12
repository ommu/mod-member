<?php
/**
 * Member Profile Documents (member-profile-document)
 * @var $this app\components\View
 * @var $this ommu\member\controllers\setting\ProfileDocumentController
 * @var $model ommu\member\models\MemberProfileDocument
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 2 October 2018, 11:36 WIB
 * @modified date 30 October 2018, 11:08 WIB
 * @link https://github.com/ommu/mod-member
 *
 */

use yii\helpers\Url;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Profile Documents'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Create');

$profile = Yii::$app->request->get('profile');
$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Back To Manage'), 'url' => Url::to(['index', 'profile'=>$profile]), 'icon' => 'table'],
];
?>

<div class="member-profile-document-create">

<?php echo $this->render('_form', [
	'model' => $model,
]); ?>

</div>
