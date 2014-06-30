<?php
/* @var $this ApiAuthController */
/* @var $model ApiAuth */

$this->breadcrumbs=array(
	'Api Keys'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List API Key', 'url'=>array('index')),
	array('label'=>'Create API Key', 'url'=>array('create')),
	array('label'=>'Update API Key', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete API Key', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage API Key', 'url'=>array('admin')),
);
?>

<h1>View API Key #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'valid_key',
	),
)); ?>
