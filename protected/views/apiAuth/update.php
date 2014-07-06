<?php
/* @var $this ApiAuthController */
/* @var $model ApiAuth */

$this->breadcrumbs=array(
	'Api Keys'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List API Key', 'url'=>array('index')),
	array('label'=>'Create API Key', 'url'=>array('create')),
	array('label'=>'View API Key', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage API Key', 'url'=>array('admin')),
);
?>

<h1>Update API Key <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>