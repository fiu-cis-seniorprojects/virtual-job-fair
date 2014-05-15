<?php
/* @var $this ScreenShareController */
/* @var $data ScreenShare */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('FK_employer')); ?>:</b>
	<?php echo CHtml::encode($data->FK_employer); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('FK_student')); ?>:</b>
	<?php echo CHtml::encode($data->FK_student); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('date')); ?>:</b>
	<?php echo CHtml::encode($data->date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('time')); ?>:</b>
	<?php echo CHtml::encode($data->time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('session_key')); ?>:</b>
	<?php echo CHtml::encode($data->session_key); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('notification_id')); ?>:</b>
	<?php echo CHtml::encode($data->notification_id); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('ScreenShareView')); ?>:</b>
	<?php echo CHtml::encode($data->ScreenShareView); ?>
	<br />

	*/ ?>

</div>