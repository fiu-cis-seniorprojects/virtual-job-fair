<?php
/* @var $this ProfileController */



$this->breadcrumbs=array(
	'Profile'=>array('/profile'),
	'image',
);
?>

<?php
/* @var $this UserController */
/* @var $user User */
/* @var $form CActiveForm */
?>




<input type="hidden" name="hidden" value="YES" />


<?php $form = $this->beginWidget('CActiveForm', array(
   'id'=>'user-EditStudent-form', 'action'=> '/JobFair/index.php/Profile/uploadImage',
   'enableAjaxValidation'=>false,
   'htmlOptions' => array('enctype' => 'multipart/form-data',),
)); ?>


  

<div  id="profileImage">
<div id="upload">
<img style="width:200px; 
	height:215px;" src="<?php echo $user->image_url ?>" />
	 </div>


	<a id="uploadlink" href="#"><img src='/JobFair/images/ico/add.gif'/></a>
	 <?php echo CHtml::activeFileField($user, 'image_url'); ?>  
	
</div>

<input type="submit" value="submit">
<?php $this->endWidget(); ?>



