<!-- To change this template, choose Tools | Templates and open the template in the editor. -->

<!DOCTYPE html>

<html>
    <head>
        <title> </title>
<?php  
  $baseUrl = Yii::app()->baseUrl; 
  $cs = Yii::app()->getClientScript();
  $cs->registerCssFile($baseUrl.'/css/style.css');
?>
        
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
         <link rel="stylesheet" type="text/css" href="style.css" />
    <!-- Prettify Code -->
    <script type="text/javascript" src="http://srprog-fall13-01.cs.fiu.edu:8080/demos/js/prettify/prettify.js"></script>

    <!-- Assumes global locations for socket.io.js and easyrtc.js -->
	<script src=http://srprog-fall13-01.cs.fiu.edu:8080/socket.io/socket.io.js></script>
	<script type="text/javascript" src="http://srprog-fall13-01.cs.fiu.edu:8080/js/easyrtc.js"></script>
	<script type="text/javascript" src="http://srprog-fall13-01.cs.fiu.edu:8080/demos/js/demo_audio_video.js"></script>         
   
        <script>

               var view_value = '<?php echo $view; ?>' ;    //getQuerystring('view');
               var notification_value = '<?php echo $notificationRead; ?>' ;  //getQuerystring('notificationRead');
               var usertype = '<?php echo $usertype; ?>' ; //getQuerystring('usertype');

            function getQuerystring(key, default_)
            {
              if (default_==null) default_=""; 
              key = key.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
              var regex = new RegExp("[\\?&]"+key+"=([^&#]*)");
              var qs = regex.exec(window.location.href);
              if(qs == null)
                return default_;
              else
                return qs[1];
            }
            
        </script>
        
        

    </head>

    <body>
        
        
<script>
    
var isFirefox = typeof InstallTrigger !== 'undefined';
    
    if (!window.chrome && !isFirefox) {
var answer = confirm ("Your browser does not support WebRTC. Click OK to get Google Chrome")
if (answer){
window.location="https://www.google.com/intl/en/chrome/browser/";
}
else {
    window.location="http://srprog-fall13-01.cs.fiu.edu/JobFair/index.php";
}
    }
</script>

        <div id="content">
                   <script src="http://srprog-fall13-01.cs.fiu.edu/JobFair/js/jquery-1.10.2.min.js"></script>
				<script src="http://srprog-fall13-01.cs.fiu.edu/lcb-text-editor-test/websockets/socket.io/node_modules/socket.io/node_modules/socket.io-client/dist/socket.io.js"></script>
               <!--  this container is for the right hand side, which contains the shared features -->
                <div id='right' style='float:right; width: 67%; height:auto; padding-top:48px;'> 
					
					<!-- begin Luis B script -->
					<script>
					var local_user = '<?php echo Yii::app()->user->name; ?>';
					var remote_user = '<?php echo $view; ?>';
					var active_document_local_url = '';
					var active_document_share_url = '';
					var socket = io.connect('http://srprog-fall13-01.cs.fiu.edu:1337');
					socket.emit('register', { from: local_user }); //register to socket
  
  					socket.on('newmessage', function (data) {
						console.log(data);
						if ( !(typeof data.url === 'undefined') ){
							console.log(data.url);
							confirm_join_page(data.from, data.url);
						}
					});
					function confirm_join_page(from, url){
						var r = confirm(from + ' has invited you to join: ' + url);
						if ( r == true ) {
							console.log('user has joined');
							var st = "<?php header('X-Frame-Options: GOFORIT');?>";
							document.getElementById("feature_iframe").src = url;
						} else {
							console.log('user has DECLINED to join');
						}
					}
					function parentLog(){
						//feature_iframe.contentWindow.history.back(); 
						console.log('parentLog was called');
					}
					function getRemoteUser(){
						return remote_user;
					}
					function getLocalUser(){
						return local_user;
					}
					function doImport(document_name){
						console.log("do import called");
						$.getJSON("/JobFair/index.php/document/ImportDocument?lU="+ local_user + "&rU=" + remote_user + "&dI=" + document_name + " ", 
						function (data) {
							console.log(document_name+ 'imported ');
							console.log(data);
						});					
					}
					function toggleMenuHide(){
						$( "#document_menu_iframe" ).slideUp( "slow", function() {
						// Animation complete.
						});
					}
					function toggleMenuShow(){
						$( "#document_menu_iframe" ).slideDown( "slow", function() {
						// Animation complete.
						});
					}
					function openDocument(data){
						console.log('open_document clicked');
						console.log(data.local_user_url);
						active_document_share_url = data.remote_user_url;
						document.getElementById("feature_iframe").src = data.local_user_url;
					}
					function refreshDocs(){
						document.getElementById("feature_iframe").src ='http://srprog-fall13-01.cs.fiu.edu/JobFair/index.php/document/ListDocument'
						var state = $( "#document_menu_iframe" ).is(':visible');
						if (state = 'false'){
							toggleMenuHide();
						}
					}
					$(function(){
						$("#create_document").click(function() {
							console.log('create_document clicked');	  
							 $("#feature_iframe").show();

							$.getJSON("<?php echo $this->createUrl('document/CreateDocument',array('rU'=> $view));?>", 
							function (data) {
								console.log(data.local_user_url);
								document.getElementById("feature_iframe").src = data.local_user_url;
								
								socket.emit('share_url', { from: local_user , to: remote_user, url: data.remote_user_url});
							});
						});
					});
					$(function(){
						$("#share_document").click(function() {
							console.log('share_document clicked');	  
 								event.preventDefault();
    							socket.emit('privmessage', { from: local_user , to: remote_user, msg: local_user + ' says hi'});
    							socket.emit('share_url', { from: local_user , to: remote_user, url: active_document_share_url });
						});
					});
					$(function(){
						$("#manage_documents").click(function() {
							console.log('manage_documents clicked');	  
 							document.getElementById("feature_iframe").src ='http://srprog-fall13-01.cs.fiu.edu/JobFair/index.php/document/ListDocument'
							var state = $( "#document_menu_iframe" ).is(':visible');
							if (state = 'false'){
								toggleMenuHide();
							}
						});
					});					
					</script>
					<!-- end Luis B script -->
					
					<!-- begin available interview services script -->
                	<script>
					$(function(){
						$("#display_document").click(function() {
							console.log('display_document clicked');
							$("#document_menu_iframe").css('display','block');
							$("#screenshare_menu_iframe").css('display','none');
							$("#whiteboard_menu_iframe").css('display','none');
							document.getElementById("feature_iframe").src = 'about:blank';	  
						});
					});
					$(function(){
						$("#display_screenshare").click(function() {
							console.log('display_screenshare clicked');
							$("#screenshare_menu_iframe").css('display','block');
							$("#document_menu_iframe").css('display','none');
							$("#whiteboard_menu_iframe").css('display','none');
							document.getElementById("feature_iframe").src = 'about:blank';	  
						});
					});
					$(function(){
						$("#display_whiteboard").click(function() {
							console.log('display_whiteboard clicked');
							$("#whiteboard_menu_iframe").css('display','block');
							$("#document_menu_iframe").css('display','none');
							$("#screenshare_menu_iframe").css('display','none');
							document.getElementById("feature_iframe").src = 'about:blank';	  
						});
					});                	
                	</script>
                	<!-- end available interview services script -->
                	
                <!-- Place collaborative editor menu here -->
                                <div id="document_menu_iframe" src="" width="800px" height="21px" style ="display:none;">
                                <div id="quick_menu" width="48px" height="48px" style="float:left;padding-right:10px;border-right:2px #6592A6 solid;">
                                <center><img src="http://srprog-fall13-01.cs.fiu.edu/JobFair/images/imgs/vjf.jpg" ></center>
                                <h9 style="color: rgb(10, 118, 231);">Quick Menu</h9>
                                </div>
                                <div id="create_document" width="48px" height="48px" style="float:left;padding-right:10px;padding-left:10px;">
                                <center>
                                <img src="http://srprog-fall13-01.cs.fiu.edu/JobFair/images/imgs/new_normal.png" id="create_document" alt="new" style="cursor:pointer; "onmouseover="this.src='http://srprog-fall13-01.cs.fiu.edu/JobFair/images/imgs/new_hover.png'" 
onmouseout="this.src='http://srprog-fall13-01.cs.fiu.edu/JobFair/images/imgs/new_normal.png'" >
								<br>new
								</center>
								</div>
								<div id="share_document" width="48px" height="48px"style="float:left;padding-right:10px;">
                                <center>
                                <img src="http://srprog-fall13-01.cs.fiu.edu/JobFair/images/imgs/share_normal.png" id="share_document" alt="share" style="cursor:pointer; "onmouseover="this.src='http://srprog-fall13-01.cs.fiu.edu/JobFair/images/imgs/share_hover.png'" 
onmouseout="this.src='http://srprog-fall13-01.cs.fiu.edu/JobFair/images/imgs/share_normal.png'" >
								<br>share
								</center>
								</div>
								<div id="manage_documents" width="48px" height="48px"style="float:left;padding-right:10px;">
                                <center>
                                <img src="http://srprog-fall13-01.cs.fiu.edu/JobFair/images/imgs/manage_normal.png" id="share_document" alt="share" style="cursor:pointer; "onmouseover="this.src='http://srprog-fall13-01.cs.fiu.edu/JobFair/images/imgs/manage_hover.png'" 
onmouseout="this.src='http://srprog-fall13-01.cs.fiu.edu/JobFair/images/imgs/manage_normal.png'" >
								<br>manage
								</center>
								</div>
<!-- 
                	<?php //bootstrap for Collaborative Document button
					$this->widget('bootstrap.widgets.TbButton', array(
					'label'=>'Create New Document',
					'type'=>'primary', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
					'size'=>'null', // null, 'large', 'small' or 'mini'
					'htmlOptions'=>array('id' => 'create_document'),
					)); ?>

					<?php //bootstrap for Collaborative Document button
					$this->widget('bootstrap.widgets.TbButton', array(
					'label'=>'Share Active Document',
					'type'=>'primary', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
					'size'=>'null', // null, 'large', 'small' or 'mini'
					'htmlOptions'=>array('id' => 'share_document'),
					)); ?>
					
					<?php //bootstrap for Collaborative Document button
					$this->widget('bootstrap.widgets.TbButton', array(
					'label'=>'Manage Documents',
					'type'=>'primary', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
					'size'=>'null', // null, 'large', 'small' or 'mini'
					'htmlOptions'=>array('id' => 'manage_documents'),
					)); ?>
 -->
                </div>
                
             <!-- Place screen share menu here -->
                
                
                
                
                
                <div id="screenshare_menu_iframe" src="" width="800px" height="21px" style ="display:none;">
                 
                          <div id="share_screen" width="48px" height="48px" style="float:left;padding-right:10px;padding-left:10px;">
                              
                                 <?php echo CHtml::image(Yii::app()->request->baseUrl."/images/ico/sharenormal.png","share screen",array('id'=> 'Sharebutton', 'style'=>'cursor:pointer')); ?>
								<br>Share
								
						</div>
                
                  
                  
                  
                <div id="view_screen" width="48px" height="48px" style="float:left;padding-right:10px;padding-left:10px;">
                                
                                 <?php echo CHtml::image(Yii::app()->request->baseUrl."/images/ico/view.png","view screen",array('id'=> 'viewscreen', 'style'=>'cursor:pointer')); ?>
								<br>View
								
				</div>
                  


                 </div>


                <!-- Place whiteboard menu here -->
                <div id="whiteboard_menu_iframe" src="" width="800px" height="21px" style ="display:none;">

<style>
.whiteboard_button {float:left; margin-left:10px;}
</style>

		                <form enctype="multipart/form-data">
<input type="file" name="file" id="file_upload" class="whiteboard_button" />
<input type="hidden" name="sessionID" value="<?php echo $session; ?>">
<input type="image" src="http://srprog-fall13-01.cs.fiu.edu/JobFair/images/save.png" value="Submit Drawing" name="file_upload" id="file" class="whiteboard_button"
        onClick="fileUpload(this.form,'http://srprog-fall13-01.cs.fiu.edu/JobFair/protected/controllers/FileUpload.php','upload'); return false;" >
<div id="upload"></div>
</form>

                <input type="image" src="http://srprog-fall13-01.cs.fiu.edu/JobFair/images/View.png" name="view_drawing" id="view_drawing" value="View Drawing" class="whiteboard_button" onclick="viewDrawing()">
                </div>
                
                <!-- This iFrame is the placeholder where the shared features get actually loaded -->
                <iframe id="feature_iframe" src="" width="800px" height="600px" style ="border: 1px #E0E0EB solid;"></iframe>
                
                </div>
            <div id="left" style="padding-top:20px;">
            <div id="videos">
               <video id="callerVideo"></video>
                 <video autoplay="autoplay" class="EasyRTCMirror" id="selfVideo" muted="true" volume="0"></video>
          
            <div id="connectControls">
                <input type="checkbox" checked="true" id="shareAudio" />Share audio
                <input type="checkbox" checked="true" id="shareVideo" />Share video <br />
                <button id="connectButton" class="btn btn-success btn-small" onclick="connect()">Start</button>
                <button id="hangupButton" class="btn btn-danger btn-small" disabled="disabled" onclick="hangup()">Hangup</button>
                <!-- <button id="disconnectButton" disabled="disabled" onclick="disconnect()">Disconnect</button> -->
                <div id="iam">Not yet connected...</div>
                <br />
                <strong>Interview User:</strong>
                <hr>
                <div id="otherClients"></div>
            </div>  
                   <div id="acceptCallBox"> <!-- Should be initially hidden using CSS -->
                        <div id="acceptCallLabel"></div>
                        <button id="callAcceptButton" >Accept</button> <button id="callRejectButton">Reject</button>
                   </div>
                 
                 <div style="clear: both"></div>
                 
                 <!-- 
                 	   This Div container holds the buttons to show a particular feature 
                 	   e.g. collaborative editor, screenshare, whiteboard, they're the buttons
                 	   on the left
                 -->
                <div id="receiveMessageArea" style='height:150px;'>
                    Available Interview Services:
						<?php //bootstrap for Collaborative Document button
						$this->widget('bootstrap.widgets.TbButton', array(
						'label'=>'Collaborative Editor',
						'type'=>'primary', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
						'size'=>'null', // null, 'large', 'small' or 'mini'
						'block'=>true,
						'htmlOptions'=>array('id' => 'display_document'),
						 )); ?>
                  		<?php //bootstrap for Collaborative Document button
						$this->widget('bootstrap.widgets.TbButton', array(
						'label'=>'Screen Share',
						'type'=>'primary', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
						'size'=>'null', // null, 'large', 'small' or 'mini'
						'block'=>true,
						'htmlOptions'=>array('id' => 'display_screenshare'),
						 )); ?>
						 <?php //bootstrap for Collaborative Document button
						$this->widget('bootstrap.widgets.TbButton', array(
						'label'=>'Whiteboard',
						'type'=>'primary', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
						'size'=>'null', // null, 'large', 'small' or 'mini'
						'block'=>true,
						'htmlOptions'=>array('id' => 'display_whiteboard'),
						 )); ?>
                    <div id="conversation">
                    <div id="conversation">
                    <div id="conversation">
                  </div>	  
                </div>
<!-- 
                <div id="sendMessageArea">
                    <div id="iam">Write your message:</div>
                    <textarea id="sendMessageText"></textarea>
                    <div id="otherClients2"></div>
                </div>
 -->
            </div>
            </div>
        </div> 
		 </div>
		 </div> 
<div id="container"> </div>

<script type="text/javascript" src="http://api.screenleap.com/js/screenleap.js"></script>
<script type="text/javascript">



window.onload = function () {
    var element = document.getElementById('selfVideo');
    element.muted = "muted";
   element.muted = "muted";


}






// get screenShare information
$(function(){
  $("#Sharebutton").click(function() {
	  $.getJSON("<?php echo $this->createUrl('screenShare/GetScreenLeap',array('session'=> $session));?>", 
    function (data) {
		  if(data.a){                      //if active screenShare
			  alert(data.a);
		  } else {  //inactive screenShare
		  screenleap.startSharing('DEFAULT', data);
		  }
	  });
  	});
});

//hides screenShare iframe
var x  = true;

$(function(){
	$("#viewscreen").click( function() { 
		//if(x){
			  $.get("<?php echo $this->createUrl('screenShare/GetviewerUrl',array('session'=> $session));?>", 
					    function (data) {
						//$("#feature_iframe").css('display','none');//lcb	  
						//$("#container").empty();
						//$("#container").append("<iframe src='" + data + "&fitToWindow=true' id ='iframe1'></iframe>");
						
						if(data == "/JobFair/index.php/screenShare/index")
							document.getElementById("feature_iframe").src = data ;
						else
							document.getElementById("feature_iframe").src = data + '&fitToWindow=true';
						
						
						$("#feature_iframe").css('display','none');
						
						
						$("#feature_iframe").slideDown();
						  });
			// $("#feature_iframe").show();
			x = false;
		//}else {
			//$("#feature_iframe").slideUp();
			x = true;
		//}
	});
});

// show view button after screenshare end


$(function(){
	screenleap.onScreenShareEnd = function() { 
		$("#view_screen").slideDown();
		$("#feature_iframe").slideDown();

	$.get("<?php echo $this->createUrl('screenShare/Setstop',array('session'=> $session));?>");
		
	};

	screenleap.onScreenShareStart = function(){
			//$("#view_screen").slideUp();
			//$("#feature_iframe").slideUp();
			x = true;
		   
		};


$("#Sharebutton").hover(
	function() {
			$(this).attr("src", "<?php echo Yii::app()->request->baseUrl."/images/ico/sharehover.png"?>");
	},
	
	function() {
		$(this).attr("src", "<?php echo Yii::app()->request->baseUrl."/images/ico/sharenormal.png"?>");
}
);


$("#viewscreen").hover(
		function() {
				$(this).attr("src", "<?php echo Yii::app()->request->baseUrl."/images/ico/viewhover.png"?>");
		},
		
		function() {
			$(this).attr("src", "<?php echo Yii::app()->request->baseUrl."/images/ico/view.png"?>");
	}
	);



		
});
 </script>

              <link rel="stylesheet" type="text/css" href="http://static.awwapp.com/plugin/1.0/aww.css"/>
              <script type="text/javascript" src="http://static.awwapp.com/plugin/1.0/aww.min.js"></script>



<script>

function hideWhiteboardButtons()
{
$( "#file" ).hide( 10 );
$( "#file_upload" ).hide( 10 );
$( "#view_drawing" ).hide( 10 );

}

function fileUpload(form, action_url, div_id) {
    // Create the iframe...
    var iframe = document.createElement("iframe");
    iframe.setAttribute("id", "upload_iframe");
    iframe.setAttribute("name", "upload_iframe");
    iframe.setAttribute("width", "0");
    iframe.setAttribute("height", "0");
    iframe.setAttribute("border", "0");
    iframe.setAttribute("style", "width: 0; height: 0; border: none;");

    // Add to document...

	    form.parentNode.appendChild(iframe);
    window.frames['upload_iframe'].name = "upload_iframe";

    iframeId = document.getElementById("upload_iframe");

    // Add event...
    var eventHandler = function () {

            if (iframeId.detachEvent) iframeId.detachEvent("onload", eventHandler);
            else iframeId.removeEventListener("load", eventHandler, false);

            // Message from server...
            if (iframeId.contentDocument) {
                content = iframeId.contentDocument.body.innerHTML;
            } else if (iframeId.contentWindow) {
                content = iframeId.contentWindow.document.body.innerHTML;
            } else if (iframeId.document) {
                content = iframeId.document.body.innerHTML;
            }

            document.getElementById(div_id).innerHTML = content;

            // Del the iframe...
            setTimeout('iframeId.parentNode.removeChild(iframeId)', 250);
        }

    if (iframeId.addEventListener) iframeId.addEventListener("load", eventHandler, true);
    if (iframeId.attachEvent) iframeId.attachEvent("onload", eventHandler);

    // Set properties of form...
    form.setAttribute("target", "upload_iframe");
    form.setAttribute("action", action_url);
    form.setAttribute("method", "post");
    form.setAttribute("enctype", "multipart/form-data");
    form.setAttribute("encoding", "multipart/form-data");


    submitDrawing();
    // Submit the form...
    form.submit();

//    document.getElementById(div_id).innerHTML = "Uploading...";
}

function submitDrawing()
{

        var submitDrawingURL = "<?php echo $this->createUrl('Whiteboard/UploadImage') ?>";

        var element = document.getElementById( "file_upload" );
        var fileName = (element.value).substring( (element.value).lastIndexOf("\\") + 1);

	if ( isImage( fileName.substring( fileName.lastIndexOf(".") + 1 ) ) == true )
	{
        var uploadRequest = $.ajax({
        url: submitDrawingURL,
        data: {image_name: fileName, session: "<?php echo $session; ?>"},
        dataType: "text",
        type: "POST"
        });

        uploadRequest.done( function( msg )
        {
	 alert( "Image uploaded successfully!" );
        });

                        uploadRequest.fail( function()
        {
		// fails
        });
	}
	else
	{
		alert( "The selected file is not an image. Please try another file" );
	}

}

function isImage( extension  )
{
	switch( extension.toLowerCase() )
	{
		case 'jpg':
		case 'jpeg':
		case 'png':
		case 'gif':
		case 'pjpeg':
		case 'x-png':

		return true;
	}

return false;
}

function viewDrawing()
{
        var viewDrawingURL = "<?php echo $this->createUrl('Whiteboard/CheckDrawingExists') ?>";

        var storeRequest = $.ajax({
        url: viewDrawingURL,
        data: {session: "<?php echo $session; ?>"},
        type: "GET",
        dataType: "text"
        });

        // needs to upload right file

        storeRequest.done( function( msg )
        {
                if( msg == "none" || msg == "" )
                {
                        alert("No drawing availabe for viewing...");
                }

				                else
                {

                        document.getElementById( "feature_iframe" ).src = "http://srprog-fall13-01.cs.fiu.edu/JobFair/images/" + msg;
                        /*
                        elem.src = "http://srprog-fall13-01.cs.fiu.edu/jobluis/images/" + msg;
                        //alert( document.getElementById( "drawn_image" ).childNodes[0] );

                        if( document.getElementById( "drawn_image" ).hasChildNodes() == true )
                                document.getElementById( "drawn_image" ).replaceChild(  elem, document.getElementById( "drawn_image" ).childNodes[ 0 ] );
                        else
                                document.getElementById( "drawn_image" ).appendChild( elem );
                        */
                }
        });

        storeRequest.fail( function()
        {
                alert( "Viewing image has failed..."  );
        });
}


$(function()
{
        $("#display_whiteboard").click( function( )
        {

document.getElementById("feature_iframe").src = "http://srprog-fall13-01.cs.fiu.edu/jobluis/protected/views/videoInterview/whiteboard.php";

$( "#file" ).fadeIn( "slow" );
$( "#file_upload" ).fadeIn( "slow" );
$( "#view_drawing" ).fadeIn( "slow" );

	var submitDrawingURL = "<?php echo $this->createUrl('Whiteboard/CreateWhiteboardSession') ?>";



        var uploadRequest = $.ajax({
        url: submitDrawingURL,
        data: {session: "<?php echo $session; ?>", user1: "<?php echo $me; ?>", user2: "<?php echo $view; ?>"},
        dataType: "text",
        type: "POST"
        });

        uploadRequest.done( function( msg )
        {
//         alert( msg );
        });

        uploadRequest.fail( function()
        {
         	alert( "There was an error. Please try again" );       // fails
        });


        });
});

</script>


     </body>
</html>
