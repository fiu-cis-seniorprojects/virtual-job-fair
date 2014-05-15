
// get collaborative editor data
$(function(){
	$("#create_document").click(function() {
		console.log('clicked');	  
		$.getJSON("<?php echo $this->createUrl('document/home',array('msg'=> 'local_user: ' . $me . '<br>remote_user: ' . $view));?>", 
       	function (data) {
			if(data.a){
  				alert(data.a);
  				alert(data.b);
  			}
  	  	});
  	});
});