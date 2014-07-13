<?php
/* @var $this UserCrudController */
/* @var $data User */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('username')); ?>:</b>
	<?php echo CHtml::encode($data->username); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('password')); ?>:</b>
	<?php echo CHtml::encode($data->password); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('FK_usertype')); ?>:</b>
	<?php echo CHtml::encode($data->FK_usertype); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('email')); ?>:</b>
	<?php echo CHtml::encode($data->email); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('registration_date')); ?>:</b>
	<?php echo CHtml::encode($data->registration_date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('activation_string')); ?>:</b>
	<?php echo CHtml::encode($data->activation_string); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('activated')); ?>:</b>
	<?php echo CHtml::encode($data->activated); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('image_url')); ?>:</b>
	<?php echo CHtml::encode($data->image_url); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('first_name')); ?>:</b>
	<?php echo CHtml::encode($data->first_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('last_name')); ?>:</b>
	<?php echo CHtml::encode($data->last_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('disable')); ?>:</b>
	<?php echo CHtml::encode($data->disable); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('has_viewed_profile')); ?>:</b>
	<?php echo CHtml::encode($data->has_viewed_profile); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('linkedinid')); ?>:</b>
	<?php echo CHtml::encode($data->linkedinid); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('googleid')); ?>:</b>
	<?php echo CHtml::encode($data->googleid); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('fiucsid')); ?>:</b>
	<?php echo CHtml::encode($data->fiucsid); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('hide_email')); ?>:</b>
	<?php echo CHtml::encode($data->hide_email); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('job_notification')); ?>:</b>
	<?php echo CHtml::encode($data->job_notification); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('fiu_account_id')); ?>:</b>
	<?php echo CHtml::encode($data->fiu_account_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('looking_for_job')); ?>:</b>
	<?php echo CHtml::encode($data->looking_for_job); ?>
	<br />

	*/ ?>

</div>