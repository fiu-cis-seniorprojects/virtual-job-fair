<?php
/* @var $this ScreenShareController */
/* @var $model ScreenShare */

$this->breadcrumbs=array(
	'Screen Shares'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List ScreenShare', 'url'=>array('index')),
	array('label'=>'Manage ScreenShare', 'url'=>array('admin')),
);
?>

<h1>Create ScreenShare</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>