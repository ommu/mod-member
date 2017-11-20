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
 * @link https://github.com/ommu/mod-member
 * @contact (+62)856-299-4114
 *
 */

	$this->breadcrumbs=array(
		'Members'=>array('manage'),
		$model->member_id,
	);
?>

<div class="dialog-content">
<?php $this->widget('application.libraries.core.components.system.FDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
			'name'=>'member_id',
			'value'=>$model->member_id,
		),
		array(
			'name'=>'publish',
			'value'=>$model->publish == '1' ? Chtml::image(Yii::app()->theme->baseUrl.'/images/icons/publish.png') : Chtml::image(Yii::app()->theme->baseUrl.'/images/icons/unpublish.png'),
			'type'=>'raw',
		),
		array(
			'name'=>'profile_id',
			'value'=>Phrase::trans($model->profile->profile_name),
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
			'type'=>'raw',
		),
		array(
			'name'=>'creation_date',
			'value'=>!in_array($model->creation_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00')) ? Utility::dateFormat($model->creation_date, true) : '-',
		),
		array(
			'name'=>'creation_id',
			'value'=>$model->creation_id != 0 ? $model->creation->displayname : '-',
		),
		array(
			'name'=>'modified_date',
			'value'=>!in_array($model->modified_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00')) ? Utility::dateFormat($model->modified_date, true) : '-',
		),
		array(
			'name'=>'modified_id',
			'value'=>$model->modified_id != 0 ? $model->modified->displayname : '-',
		),
	),
)); ?>
</div>
<div class="dialog-submit">
	<?php echo CHtml::button(Yii::t('phrase', 'Close'), array('id'=>'closed')); ?>
</div>
