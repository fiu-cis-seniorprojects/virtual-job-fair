<?php
/* @var $this ScreenShareController */
/* @var $model ScreenShare */

$this->breadcrumbs=array(
	'Screen Shares'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List ScreenShare', 'url'=>array('index')),
	array('label'=>'Create ScreenShare', 'url'=>array('create')),
	array('label'=>'View ScreenShare', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage ScreenShare', 'url'=>array('admin')),
);
?>

<h1>Update ScreenShare <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>