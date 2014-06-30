<?php
/* @var $this ApiAuthController */
/* @var $model ApiAuth */

$this->breadcrumbs=array(
	'API Keys'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List API Key', 'url'=>array('index')),
	array('label'=>'Manage API Key', 'url'=>array('admin')),
);
?>

<h1>Create API Key</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>