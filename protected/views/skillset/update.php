<?php
/* @var $this SkillsetController */
/* @var $model Skillset */

$this->breadcrumbs=array(
	'Skillsets'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Skillset', 'url'=>array('index')),
	array('label'=>'Create Skillset', 'url'=>array('create')),
	array('label'=>'View Skillset', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Skillset', 'url'=>array('admin')),
);
?>

<h1>Update Skillset <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>