<?php
/* @var $this PostingsAdminController */
/* @var $data Job */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('type')); ?>:</b>
	<?php echo CHtml::encode($data->type); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('title')); ?>:</b>
	<?php echo CHtml::encode($data->title); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('FK_poster')); ?>:</b>
	<?php echo CHtml::encode($data->FK_poster); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('post_date')); ?>:</b>
	<?php echo CHtml::encode($data->post_date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('deadline')); ?>:</b>
	<?php echo CHtml::encode($data->deadline); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('description')); ?>:</b>
	<?php echo CHtml::encode($data->description); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('compensation')); ?>:</b>
	<?php echo CHtml::encode($data->compensation); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('other_requirements')); ?>:</b>
	<?php echo CHtml::encode($data->other_requirements); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('email_notification')); ?>:</b>
	<?php echo CHtml::encode($data->email_notification); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('active')); ?>:</b>
	<?php echo CHtml::encode($data->active); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('matches_found')); ?>:</b>
	<?php echo CHtml::encode($data->matches_found); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('posting_url')); ?>:</b>
	<?php echo CHtml::encode($data->posting_url); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('comp_name')); ?>:</b>
	<?php echo CHtml::encode($data->comp_name); ?>
	<br />

	*/ ?>

</div>