<?php
/* @var $this ScreenShareController */
/* @var $model ScreenShare */
/* @Sleap_result screenshare return html*/
$js = Yii::app()->clientScript;    /// for JS library
$js->registerCoreScript('jquery.ui');


$this->breadcrumbs=array(
	'Screen Shares'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List ScreenShare', 'url'=>array('index')),
	array('label'=>'Create ScreenShare', 'url'=>array('create')),
	array('label'=>'Update ScreenShare', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete ScreenShare', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage ScreenShare', 'url'=>array('admin')),
);
?>

<h1>View ScreenShare #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'FK_employer',
		'FK_student',
		'date',
		'time',
		'session_key',
		'notification_id',
		'ScreenShareView',
	),
));



?>

<p>First Paragraph</p>




<?php //bootstrap for screen Share button
	$this->widget('bootstrap.widgets.TbButton', array(
    'label'=>'Share Screen',
    'type'=>'primary', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
    'size'=>'null', // null, 'large', 'small' or 'mini'
	 'htmlOptions'=>array('id' => 'Sharebutton'),
)); ?>


 <?php //bootstrap for screen Share button
	$this->widget('bootstrap.widgets.TbButton', array(
    'label'=>'show hide iframe',
    'type'=>'primary', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
    'size'=>'null', // null, 'large', 'small' or 'mini'
	 'htmlOptions'=>array('id' => 'viewscreen'),
)); ?>

<div id="container"> </div>







<script type="text/javascript" src="http://api.screenleap.com/js/screenleap.js"></script>
<script type="text/javascript">
// get screenShare information
$(function(){
  $("#Sharebutton").click(function() {

	  $.getJSON("/JobFair/index.php/screenShare/GetScreenLeap", 
    function (data) {
		  screenleap.startSharing('DEFAULT', data);
		  alert(JSON.stringify(data));
	  });
   
  	});
});


//hides screenShare iframe
var x  = true;

$(function(){
	$("#viewscreen").click( function() { 
		if(x){

			  $.get("/JobFair/index.php/screenShare/GetviewerUrl", 
					    function (data) {
							  
						$("#container").empty();
						$("#container").append("<iframe src='" + data + "&fitToWindow=true' id ='iframe1'></iframe>");
						$("#iframe1").css('height', 1000)
						$("#iframe1").css('width', 1000)
						  });

			
			
			$("#iframe1").show();
			x = false;
			
		}else {
			$("#iframe1").hide();
			x = true;
		}
	});
});



 </script>
 