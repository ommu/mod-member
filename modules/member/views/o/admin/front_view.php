<?php
/**
 * Members (members)
 * @var $this AdminController
 * @var $model Members
 * version: 0.0.1
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2017 Ommu Platform (opensource.ommu.co)
 * @created date 2 March 2017, 15:02 WIB
 * @link https://github.com/ommu/Members
 * @contect (+62)856-299-4114
 *
 */

	$this->breadcrumbs=array(
		'Members'=>array('manage'),
		$model->member_id,
	);
?>

<?php //begin.Messages ?>
<?php
if(Yii::app()->user->hasFlash('success'))
	echo Utility::flashSuccess(Yii::app()->user->getFlash('success'));
?>
<?php //end.Messages ?>

<?php $this->widget('application.components.system.FDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
			'name'=>'member_id',
			'value'=>$model->member_id,
			//'value'=>$model->member_id != '' ? $model->member_id : '-',
		),
		array(
			'name'=>'publish',
			'value'=>$model->publish == '1' ? Chtml::image(Yii::app()->theme->baseUrl.'/images/icons/publish.png') : Chtml::image(Yii::app()->theme->baseUrl.'/images/icons/unpublish.png'),
			//'value'=>$model->publish,
		),
		array(
			'name'=>'profile_id',
			'value'=>$model->profile_id,
			//'value'=>$model->profile_id != '' ? $model->profile_id : '-',
		),
		array(
			'name'=>'member_header',
			'value'=>$model->member_header != '' ? $model->member_header : '-',
			//'value'=>$model->member_header != '' ? CHtml::link($model->member_header, Yii::app()->request->baseUrl.'/public/visit/'.$model->member_header, array('target' => '_blank')) : '-',
			'type'=>'raw',
		),
		array(
			'name'=>'member_photo',
			'value'=>$model->member_photo != '' ? $model->member_photo : '-',
			//'value'=>$model->member_photo != '' ? CHtml::link($model->member_photo, Yii::app()->request->baseUrl.'/public/visit/'.$model->member_photo, array('target' => '_blank')) : '-',
			'type'=>'raw',
		),
		array(
			'name'=>'short_biography',
			'value'=>$model->short_biography != '' ? $model->short_biography : '-',
			//'value'=>$model->short_biography != '' ? CHtml::link($model->short_biography, Yii::app()->request->baseUrl.'/public/visit/'.$model->short_biography, array('target' => '_blank')) : '-',
			'type'=>'raw',
		),
		array(
			'name'=>'creation_date',
			'value'=>!in_array($model->creation_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00')) ? Utility::dateFormat($model->creation_date, true) : '-',
		),
		array(
			'name'=>'creation_id',
			'value'=>$model->creation_id,
			//'value'=>$model->creation_id != 0 ? $model->creation_id : '-',
		),
		array(
			'name'=>'modified_date',
			'value'=>!in_array($model->modified_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00')) ? Utility::dateFormat($model->modified_date, true) : '-',
		),
		array(
			'name'=>'modified_id',
			'value'=>$model->modified_id,
			//'value'=>$model->modified_id != 0 ? $model->modified_id : '-',
		),
	),
)); ?>

<div class="dialog-content">
</div>
<div class="dialog-submit">
	<?php echo CHtml::button(Yii::t('phrase', 'Close'), array('id'=>'closed')); ?>
</div>
