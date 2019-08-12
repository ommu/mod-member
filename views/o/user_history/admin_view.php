<?php
/**
 * Member User Details (member-user-detail)
 * @var $this UserhistoryController
 * @var $model MemberUserDetail
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @created date 7 March 2017, 23:01 WIB
 * @link https://github.com/ommu/mod-member
 *
 */

	$this->breadcrumbs=array(
		'Member User Details'=>array('manage'),
		$model->id,
	);
?>

<div class="dialog-content">
	<?php $this->widget('zii.widgets.CDetailView', array(
		'data'=>$model,
		'attributes'=>array(
			array(
				'name'=>'id',
				'value'=>$model->id,
			),
			array(
				'name'=>'publish',
				'value'=>$this->quickAction(Yii::app()->controller->createUrl('publish', array('id'=>$model->id)), $model->publish),
				'type'=>'raw',
			),
			array(
				'name'=>'member_user_id',
				'value'=>$model->member_user_id != '' ? $model->member_user_id : '-',
			),
			array(
				'name'=>'updated_date',
				'value'=>!in_array($model->updated_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')) ? $this->dateFormat($model->updated_date) : '-',
			),
			array(
				'name'=>'updated_id',
				'value'=>$model->updated_id != '' ? $model->updated->displayname : '-',
			),
		),
	)); ?>
</div>
<div class="dialog-submit">
	<?php echo CHtml::button(Yii::t('phrase', 'Close'), array('id'=>'closed')); ?>
</div>
