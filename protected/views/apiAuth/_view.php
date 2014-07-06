<?php
/* @var $this ApiAuthController */
/* @var $data ApiAuth */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('valid_key')); ?>:</b>
	<?php echo CHtml::encode($data->valid_key); ?>
	<br />


</div>