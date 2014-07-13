<?php
/* @var $this UserCrudController */
/* @var $model User */

$this->breadcrumbs=array(
	'Users'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List User', 'url'=>array('index')),
	array('label'=>'Create User', 'url'=>array('create')),
	array('label'=>'Update User', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete User', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage User', 'url'=>array('admin')),
);
?>

<h1>View User #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'username',
		'password',
		'FK_usertype',
		'email',
		'registration_date',
		'activation_string',
		'activated',
		'image_url',
		'first_name',
		'last_name',
		'disable',
		'has_viewed_profile',
		'linkedinid',
		'googleid',
		'fiucsid',
		'hide_email',
		'job_notification',
		'fiu_account_id',
		'looking_for_job',
	),
)); ?>
