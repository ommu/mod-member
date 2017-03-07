<?php
/**
 * Member User Details (member-user-detail)
 * @var $this UserhistoryController
 * @var $model MemberUserDetail
 * @var $form CActiveForm
 * version: 0.0.1
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2017 Ommu Platform (opensource.ommu.co)
 * @created date 7 March 2017, 23:01 WIB
 * @link https://github.com/ommu/Members
 * @contect (+62)856-299-4114
 *
 */

	$this->breadcrumbs=array(
		'Member User Details'=>array('manage'),
		'Create',
	);
?>

<div class="form">
	<?php echo $this->renderPartial('/o/user_history/_form', array('model'=>$model)); ?>
</div>
