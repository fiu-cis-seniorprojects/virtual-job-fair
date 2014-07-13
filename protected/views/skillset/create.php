<?php
/* @var $this SkillsetController */
/* @var $model Skillset */

$this->breadcrumbs=array(
	'Skillsets'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Skillset', 'url'=>array('index')),
	array('label'=>'Manage Skillset', 'url'=>array('admin')),
);
?>

<h1>Create Skillset</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>