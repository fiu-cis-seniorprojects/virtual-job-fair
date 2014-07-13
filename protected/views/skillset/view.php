<?php
/* @var $this SkillsetController */
/* @var $model Skillset */

$this->breadcrumbs=array(
	'Skillsets'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List Skillset', 'url'=>array('index')),
	array('label'=>'Create Skillset', 'url'=>array('create')),
	array('label'=>'Update Skillset', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Skillset', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Skillset', 'url'=>array('admin')),
);
?>

<h1>View Skillset #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
	),
)); ?>
