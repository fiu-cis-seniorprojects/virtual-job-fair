<?php

class DocumentController extends Controller
{
	/* DECLARE CONSTANTS */
	private $LANG='en';
	private $APIKEY='835dc233a4276528c2630262da701051';
	private $OUTPUT='url';
	
	/* declare variables */
	private $activeDocument;
	
	public $defaultAction = 'nop';

	// BEGIN PRIVATE FUNCTIONS

	/*
	* provide an entry point to the controller, per YII logic
	*/
	public function actionNop()
	{
		//$this->render('createDocument');
		echo CHtml::link('create shared document',array('document/CreateDocument'));
		echo "<br>";
		echo CHtml::link('join shared document',array('document/ShareDocument'));
		
		$userName = Yii::app()->user->name;
		echo "<br>local user name $userName";
		$model = User::model()->find("username=:username",array(':username'=>$userName));
		$userid=$model->id;
		echo "<br>local user id $userid";
		$qmodel = UserDocument::model()->find("active_status=:active_status",array(':active_status'=>'0'));
		$quserid=$qmodel->document_path;
		echo "$quserid";


	}
	
	/*
	* internal function, for debugging purposes
	*/
	private function dbg($msg){
		
		//echo "$msg";
	}
	
	/*
	* call Zoho API with appropriate parameters & return data
	* $uN = $local_user_name		//username of local user
	* $rN = $local_user_real_name	//real name of local user
	* $dI = $documentId 			//UUID of the document, corresponds to document_id in the db model
	* $mD = $mode					//type of mode, normal or collaboration
	* $ZDI = $remote_user_doc_id	//document Id returned from local user, needed for collaboration
	*/
	private function createNewEditor($uN, $rN, $dI, $mD, $zDI){
	
		//extract data from the post
		extract($_POST);

		$url = 'https://exportwriter.zoho.com/remotedoc.im';

		//set POST variables
		$mode=$mD;
		$filename=$dI . '.doc';
		$id=$dI;
		$format='doc';
		$realUserName=$rN;
		$saveurl='http://'.Yii::app()->request->getServerName().'/JobFair/userHomes/' . $uN . '/saveFiles/' . $dI .'.php';
		$fields_string='';
		$headers = array("Content-Type: multipart/form-data");

// 		echo "Method:createNewEditor()<br> $id <br> $realUserName <br> $saveurl <br><br>";
		
		/*
		From the curl_setopt reference: 
		" Note:
		Passing an array to CURLOPT_POSTFIELDS will encode the data as multipart/form-data"
		*/
		$fields = array(
								//'url' => urlencode($url),
								'apikey' => urlencode($this->APIKEY),
								'output' => urlencode($this->OUTPUT),
								'mode' => urlencode($mode),
								'filename' => urlencode($filename),
								'lang' => urlencode($this->LANG),
								'id' => urlencode($id),
								'format' => urlencode($format),
								'username' => urlencode($realUserName),
								'saveurl' => urlencode($saveurl)
						);

		//url-ify the data for the POST
		foreach($fields as $key=>$value) { 
			$fields_string .= $key.'='.$value.'&'; 
		}

//		echo "<br><br>$zDI<br><br>";

		if ($zDI != NULL){
			$zDI = str_replace("\n", '', $zDI);
			$zDI = str_replace("\r", '', $zDI);
			$fields_string .= 'documentid=' . urlencode($zDI);
		}
	
		$fields_string = rtrim($fields_string, '&');

//  		echo "here's the string sent: \n<br> $fields_string \n<br>";

		//open connection
		$ch = curl_init();

		//set the url, number of POST vars, POST data
		curl_setopt($ch,CURLOPT_URL, $url);
		//curl_setopt ($ch,CURLOPT_HTTPHEADER,$headers);
		curl_setopt($ch, CURLOPT_POST, count($fields));
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		//execute post
		$result = curl_exec($ch);
		$decode = $result;
// 		echo "response url is:<br> $result \n<br>\n<br>";
		//close connection
		curl_close($ch);

				// tokenize
				$tok = explode("WARNING=",$decode);
				//print_r($tok[0]);
				$ownerUrl = trim($tok[0], "URL=");
				$remoteUrl = explode("DOCUMENTID=",$decode);
				//print_r($remoteUrl[1]);

		if ($zDI == NULL){
		$results = array(
			'owner_url' => $ownerUrl,
			'viewer_document_id' => $remoteUrl[1]
			);
		} else {
		$results = array(
			'remote_url' => $ownerUrl);
		}

		return $results;

	}
	
	/*
	* call Zoho API with appropriate parameters & return data
	* $uN = $local_user_name		//username of local user
	* $rN = $local_user_real_name	//real name of local user
	* $dI = $documentId 			//UUID of the document, corresponds to document_id in the db model
	* $mD = $mode					//type of mode, normal or collaboration
	* $ZDI = $remote_user_doc_id	//document Id returned from local user, needed for collaboration
	*/
	private function openExistingEditor($uN, $rN, $dI, $mD, $zDI){
		//extract data from the post
		extract($_POST);

		$url = 'https://exportwriter.zoho.com/remotedoc.im';

		//set POST variables
		$mode=$mD;
		$filename=$dI . '.doc';
		$id=$dI;
		$format='doc';
		$realUserName=$rN;
		$saveurl='http://'.Yii::app()->request->getServerName().'/JobFair/userHomes/' . $uN . '/saveFiles/' . $dI .'.php';
		$fields_string='';
		$content_url='http://'.Yii::app()->request->getServerName().'/JobFair/userHomes/' . $uN . '/saveFiles/' . $filename;
	
		/*
		From the curl_setopt reference: 
		" Note:
		Passing an array to CURLOPT_POSTFIELDS will encode the data as multipart/form-data"
		*/
		$fields = array(
							'url' => urlencode($content_url),
							'apikey' => urlencode($this->APIKEY),
							'output' => urlencode($this->OUTPUT),
							'mode' => urlencode($mode),
							'filename' => urlencode($filename),
							'lang' => urlencode($this->LANG),
							'id' => urlencode($id),
							'format' => urlencode($format),
							'username' => urlencode($realUserName),
							'saveurl' => urlencode($saveurl)
						);

		//url-ify the data for the POST
		foreach($fields as $key=>$value) { 
			$fields_string .= $key.'='.$value.'&'; 
		}

// 		echo "<br><br>$zDI<br><br>";
 
		if ($zDI != NULL){
			$zDI = str_replace("\n", '', $zDI);
			$zDI = str_replace("\r", '', $zDI);
			$fields_string .= 'documentid=' . urlencode($zDI);
		}
	
		$fields_string = rtrim($fields_string, '&');

// 		echo "here's the string sent: \n<br> $fields_string \n<br>";

		//open connection
		$ch = curl_init();

		//set the url, number of POST vars, POST data
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, count($fields));
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		//execute post
		$result = curl_exec($ch);
		$decode = $result;
// 		echo "<strong>response url is:</strong><br> $result \n<br>\n<br>";
		//close connection
		curl_close($ch);

			// tokenize
			$tok = explode("WARNING=",$decode);
			//print_r($tok[0]);
			$ownerUrl = trim($tok[0], "URL=");
			$remoteUrl = explode("DOCUMENTID=",$decode);
			//print_r($remoteUrl[1]);

		if ($zDI == NULL){
		$results = array(
			'owner_url' => $ownerUrl,
			'viewer_document_id' => $remoteUrl[1]
			);
		} else {
		$results = array(
			'remote_url' => $ownerUrl);
		}

		return $results;	
	}
	
	/*
	* setup the user's home folder and save urls
	*/
	private function prepareHomeFolder($username, $documentId){
		$user=$username;
		$homeFolder = '/var/www/html/JobFair/userHomes';
		$userHomeFolder = $homeFolder . "/" . $user;
		$userSaveFiles = $userHomeFolder . "/saveFiles";

		/* The group developers must exist in the system and ideally contains at least apache and php */
		$group_name = "developers";

		// if user's document folder doesn't exist, create it
		if (!file_exists($userHomeFolder)) {
			// create folders
			mkdir($userHomeFolder, 0770);
			mkdir($userSaveFiles, 0770);
			// set correct group
			chgrp($userHomeFolder, $group_name);
			chgrp($userSaveFiles, $group_name);
			// failsafe set correct permissions
			system("/bin/chmod -R ug+rwx $userHomeFolder");
			system("/bin/chmod -R o+rx $userHomeFolder");
		}
			$fh = fopen($userSaveFiles . "/" . $documentId . '.php', 'w') or die("can't open file");
			
			$stringData = "<?php\n\$filepath = '";
			$stringData .= $userSaveFiles . "/" . $documentId . ".doc';";
			$stringData .= "\n\$tmp_filename = \$_FILES['content']['tmp_name'];";
			$stringData .= "\n\$upload_status = move_uploaded_file(\$tmp_filename, \$filepath);";
			
			$stringData .= "\n?>\n";
			fwrite($fh, $stringData);
			fclose($fh);
	}
	
	/*
	* save info in database
	*/
	private function saveDb($recordArray){
		$model=new UserDocument;
		foreach ($recordArray as $key => $value) {
			//echo "$key $value\n<br>";
			$model->$key = $value;
		}
		$model->save();
	}

	/*
	* clear all active documents in db
	*/
	private function markDocumentsInactive(){
		// local user variables
		$local_user_name = Yii::app()->user->name;
		$model = User::model()->find("username=:username",array(':username'=>$local_user_name));
		$local_user_id = $model->id;
		$local_user_real_name = $model->first_name . ' ' . $model->last_name;
		
		$condition = 'owner_id = ' . $local_user_id;
		// this is clearing all active user documents
		$document = UserDocument::model()->updateAll(array( 'active_status' => 0 ), $condition );
	}
	
	// END PRIVATE FUNCTIONS
	
	// BEGIN PUBLIC FUNCTIONS
	
	public function actionCreateDocument($rU)
	{
		//$this->render('createDocument');

		// common variables
		$documentId = uniqid();
		
		// local user variables
		$local_user_name = Yii::app()->user->name;
		$model = User::model()->find("username=:username",array(':username'=>$local_user_name));
		$local_user_id = $model->id;
		$local_user_real_name = $model->first_name . ' ' . $model->last_name;
		$mode = 'collabedit';
		
		//remote user varaibles
		$remote_user_name = $rU;
		$rmodel = User::model()->find("username=:username",array(':username'=>$remote_user_name));
		$remote_user_id = $rmodel->id;
		$remote_user_real_name = $rmodel->first_name . ' ' . $rmodel->last_name;
		$rmode = 'normaledit';
						
//  		echo "Method:actionCreateDocument()<br>";		
//  		echo "username: $local_user_name\n<br>";
//  		echo "user id : $local_user_id\n<br><br>";
//  		echo "remote username: $remote_user_name\n<br>";
//  		echo "remote user id : $remote_user_id\n<br><br>";

		// prepare user's home folder
 		$this->prepareHomeFolder($local_user_name, $documentId);
	
		// make a call to Zoho API on behalf of local user
		$local_user_url_arr = $this->createNewEditor($local_user_name, $local_user_real_name, $documentId, $mode, NULL);
		$keys = array_keys($local_user_url_arr);
		$ownerUrl = $local_user_url_arr[$keys[0]];
		$remote_user_doc_id = $local_user_url_arr[$keys[1]];
		
		//echo json_encode($local_user_url_arr);
		
// 		echo "Method:actionCreateDocument()<br> $ownerUrl <br> $remote_user_doc_id<br><br>";
		
		//	make a call to Zoho API on behalf of remote	user
		//											1st parameter MUST be local
		$remote_user_url_arr = $this->createNewEditor($local_user_name, $remote_user_real_name, $documentId, $rmode, $remote_user_doc_id);
		$keys = array_keys($remote_user_url_arr);
		$viewerUrl = $remote_user_url_arr[$keys[0]];
		
		//echo json_encode($remote_user_url_arr);		
				
		$records = array(
			'active_status' => '1',
			'document_id' => $documentId, 
			'local_user_id' => $local_user_id, 
			'remote_user_id' => $remote_user_id,
			'owner_id' => $local_user_id,
			'document_path' => '/var/www/html/JobFair/userHomes/' . $local_user_name . '/',
			'document_name' => $documentId . '.doc',
			'owner_url' => $ownerUrl,
			'viewer_url' => $viewerUrl,
			);
		
		// clear all active documents	
		$this->markDocumentsInactive();
		
		// save db with new document, which will be active
		$this->saveDb($records);
		
		$ownerUrl = str_replace("\n", '', $ownerUrl);
		//$ownerUrl = str_replace("\r", '', $ownerUrl);
		$viewerUrl = str_replace("\n", '', $viewerUrl);
		//$viewerUrl = str_replace("\r", '', $viewerUrl);
		
		$reponse = array(
			'local_user_url' => $ownerUrl,
			'remote_user_url' => $viewerUrl
			);
		
		echo json_encode($reponse);
	}

	public function actionDeleteDocument($document)
	{
		
		// local user variables
		$local_user_name = Yii::app()->user->name;
		$model = User::model()->find("username=:username",array(':username'=>$local_user_name));
		$local_user_id = $model->id;
		$local_user_real_name = $model->first_name . ' ' . $model->last_name;
		
		$user=$local_user_name;
		$homeFolder = '/var/www/html/JobFair/userHomes';
		$userHomeFolder = $homeFolder . "/" . $user;
		$userSaveFiles = $userHomeFolder . "/saveFiles";
		
		$docId = substr($document, 0, -4);
		$condition = 'owner_id = ' . $local_user_id;
		// this is clearing all active user documents
		echo "docId = ".$docId;
		echo "<br>";
		echo "document = ".$document;
		
		$del = UserDocument::model()->find('document_id=:document_id AND owner_id=:owner_id',
		array(
		  ':document_id'=>$docId,
		  ':owner_id'=>$local_user_id,
		));
		$del->delete();
		
		//echo "<br>file to delete= ".$userSaveFiles . "/" . $document;
		
		unlink($userSaveFiles . "/" . $docId . ".doc");
		unlink($userSaveFiles . "/" . $docId . ".php");
				
		$reponse = array( $document => 'was deleted' );
		echo json_encode($reponse);
		//$this->render('deleteDocument');
	}

	public function actionExportDocument()
	{
		//implemented as JavaScript
		//$this->render('exportDocument');
	}

	public function actionHome($msg)
	{
		$this->dbg($msg);
		//$this->render('home');
	}

	public function actionImportDocument($lU, $rU, $dI)
	{
// echo $lU;
// echo $rU;
// echo $dI;

		$user=$lU;
		$homeFolder = '/var/www/html/JobFair/userHomes';
		$userHomeFolder = $homeFolder . "/" . $user;
		$userSaveFiles = $userHomeFolder . "/saveFiles";

		// local user variables
		$local_user_name = Yii::app()->user->name;
		$model = User::model()->find("username=:username",array(':username'=>$local_user_name));
		$local_user_id = $model->id;
		$local_user_real_name = $model->first_name . ' ' . $model->last_name;
		
		//remote user varaibles
		$remote_user_name = $rU;
		$rmodel = User::model()->find("username=:username",array(':username'=>$remote_user_name));
		$remote_user_id = $rmodel->id;
		$remote_user_real_name = $rmodel->first_name . ' ' . $rmodel->last_name;

		/* The group developers must exist in the system and ideally contains at least apache and php */
		$group_name = "developers";

		$fh = fopen($userSaveFiles . "/" . $dI . '.php', 'w') or die("can't open file");
		
		$stringData = "<?php\n\$filepath = '";
		$stringData .= $userSaveFiles . "/" . $dI . ".doc';";
		$stringData .= "\n\$tmp_filename = \$_FILES['content']['tmp_name'];";
		$stringData .= "\n\$upload_status = move_uploaded_file(\$tmp_filename, \$filepath);";
		
		$stringData .= "\n?>\n";
		fwrite($fh, $stringData);
		fclose($fh);
		
		$records = array(
			'active_status' => '1',
			'document_id' => $dI, 
			'local_user_id' => $local_user_id, 
			'remote_user_id' => $remote_user_id,
			'owner_id' => $local_user_id,
			'document_path' => '/var/www/html/JobFair/userHomes/' . $local_user_name . '/',
			'document_name' => $dI . '.doc',
		);
	
		$model=new UserDocument;
			foreach ($records as $key => $value) {
			//echo "$key $value\n<br>";
			$model->$key = $value;
		}
		
		$model->save(false);	
		
			// save db with new document, which will be active
			//$this->saveDb($records);
			
	}

	public function actionListDocument()
	{
		$local_user_name = Yii::app()->user->name;
		$model = User::model()->find("username=:username",array(':username'=>$local_user_name));
		$local_user_id = $model->id;// mode list documents

			// prep page
			echo'
			<link href="/lcb-text-editor-test/Zoho/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
			<link href="/lcb-text-editor-test/Zoho/bootstrap/css/stylesheet.css" rel="stylesheet" media="screen">
			<!-- <script src="http://code.jquery.com/jquery.js"></script> -->
			<script src="http://".Yii::app()->request->getServerName()."/JobFair/js/jquery-1.10.2.min.js"></script>
			<script src="/lcb-text-editor-test/Zoho/bootstrap/js/bootstrap.min.js"></script>
			<script src="/lcb-text-editor-test/Zoho/bootstrap/js/animations.js"></script>
			<script>
			var markedForDeletion;
			function setSelected(docname){
				console.log(docname + \' clicked\');
				markedForDeletion = docname;
				console.log(markedForDeletion + \' is selected for action\');
			}
			$(function(){
				$("#open_selected_document").click(function() {
					console.log(\'open_selected_document clicked\');	  
					parent.toggleMenuShow();
					if (typeof markedForDeletion != "undefined"){
						console.log(markedForDeletion);
						var r_u = parent.getRemoteUser();
						var doc = markedForDeletion.substring(0, markedForDeletion.length - 4)
						$.getJSON("/JobFair/index.php/document/openDocument?rU=" + r_u + "&dI=" + doc + " ", 
							function (data) {
							console.log(data);
							parent.openDocument(data);
						});
					} else {
						alert("you must select a document from below to perform this action");
					}
				});
			});
			$(function(){
				$("#delete_selected_document").click(function() {
					if (typeof markedForDeletion != "undefined"){
						var r = confirm("Are you sure you want to delete: " + markedForDeletion);
						if ( r == true ) {
							console.log(\'the doucment would have been deleted\');
							$.getJSON("/JobFair/index.php/document/deleteDocument?document=" + markedForDeletion + " ", 
							function (data) {
								console.log(data);
								markedForDeletion = "";
							});
						} else {
							console.log("user has CANCELED deletion");
						}
					} else {
						alert("you must select a document from below to perform this action");
					}
				});
			});
			$(function(){
				$("#rename_selected_document").click(function() {
					if (typeof markedForDeletion != "undefined"){
						var newDocumentName=prompt("Please enter a new document name for document: ","");

						if (newDocumentName!=null) {
							console.log(markedForDeletion + "document would have been renamed-> " + newDocumentName);
							$.getJSON("/JobFair/index.php/document/renameDocument?document=" + markedForDeletion + "&newName=" + newDocumentName + " ", 
							function (data) {
								console.log(data);
							});
						} else {
							console.log("user has CANCELED rename");
						}
					} else {
						alert("you must select a document from below to perform this action");
					}
				});
			});
			$(function(){
				$("#export_selected_document").click(function() {
					if (typeof markedForDeletion != "undefined"){
						console.log("marked for export is: "+markedForDeletion);
						window.open("/JobFair/userHomes/' . $local_user_name . '/saveFiles/"+markedForDeletion);
						parent.parentLog();
					} else {
						alert("you must select a document from below to perform this action");
					}
				});
			});
			$(function(){
				$("#import_selected_document").click(function() {
					self.location = "http://".Yii::app()->request->getServerName()."/JobFair/protected/controllers/FileUpload.html";
				});
			});			
			$(function(){
				$("#cancel_selected_document").click(function() {
					parent.parentLog();
					parent.toggleMenuShow();
				});
			});
			</script>

			<div id="open_document" width="48px" height="48px" style="position:relative;float:left;padding-right:10px;padding-left:10px;overflow:hidden;">
				
					<img src="http://".Yii::app()->request->getServerName()."/JobFair/images/imgs/open_normal.png" id="open_selected_document" alt="new" style="left:14px;cursor:pointer; "onmouseover="this.src=\'http://".Yii::app()->request->getServerName()."/JobFair/images/imgs/open_hover.png\'" onmouseout="this.src=\'http://".Yii::app()->request->getServerName()."/JobFair/images/imgs/open_normal.png\'" >
				<center>
					<br><br>open
				</center>
			</div>
			<div id="import_document" width="48px" height="48px" style="position:relative;float:left;padding-right:10px;padding-left:10px;overflow:hidden;">

					<img src="http://".Yii::app()->request->getServerName()."/JobFair/images/imgs/import_normal.png" id="import_selected_document" alt="new" style="left:14px;cursor:pointer; "onmouseover="this.src=\'http://".Yii::app()->request->getServerName()."/JobFair/images/imgs/import_hover.png\'" onmouseout="this.src=\'http://".Yii::app()->request->getServerName()."/JobFair/images/imgs/import_normal.png\'" >
				<center>
					<br><br style="font-size:8px;">import
				</center>
			</div>

			<div id="export_document" width="48px" height="48px" style="position:relative;float:left;padding-right:10px;padding-left:10px;overflow:hidden;">

					<img src="http://".Yii::app()->request->getServerName()."/JobFair/images/imgs/export_normal.png" id="export_selected_document" alt="new" style="left:14px;cursor:pointer; "onmouseover="this.src=\'http://".Yii::app()->request->getServerName()."/JobFair/images/imgs/export_hover.png\'" onmouseout="this.src=\'http://".Yii::app()->request->getServerName()."/JobFair/images/imgs/export_normal.png\'" >
				<center>
					<br><br style="font-size:8px;">export
				</center>
			</div>
			
			<div id="delete_document" width="48px" height="48px" style="position:relative;float:left;padding-right:10px;padding-left:10px;overflow:hidden;">

					<img src="http://".Yii::app()->request->getServerName()."/JobFair/images/imgs/delete_normal.png" id="delete_selected_document" alt="new" style="left:14px;cursor:pointer; "onmouseover="this.src=\'http://".Yii::app()->request->getServerName()."/JobFair/images/imgs/delete_hover.png\'" onmouseout="this.src=\'http://".Yii::app()->request->getServerName()."/JobFair/images/imgs/delete_normal.png\'" >
				<center>
					<br><br style="font-size:8px;">delete
				</center>
			</div>		
				
			<div id="rename_document" width="48px" height="48px" style="position:relative;float:left;padding-right:10px;padding-left:10px;overflow:hidden;">

					<img src="http://".Yii::app()->request->getServerName()."/JobFair/images/imgs/rename_normal.png" id="rename_selected_document" alt="new" style="left:14px;cursor:pointer; "onmouseover="this.src=\'http://".Yii::app()->request->getServerName()."/JobFair/images/imgs/rename_hover.png\'" onmouseout="this.src=\'http://".Yii::app()->request->getServerName()."/JobFair/images/imgs/rename_normal.png\'" >
				<center>
					<br><br style="font-size:8px;">rename
				</center>
			</div>		

			<div id="cancel_document" width="48px" height="48px" style="position:relative;float:left;padding-right:10px;padding-left:10px;overflow:hidden;border-right:2px #6592A6 solid;">

					<img src="http://".Yii::app()->request->getServerName()."/JobFair/images/imgs/cancel_normal.png" id="cancel_selected_document" alt="new" style="left:14px;cursor:pointer; "onmouseover="this.src=\'http://".Yii::app()->request->getServerName()."/JobFair/images/imgs/cancel_hover.png\'" onmouseout="this.src=\'http://".Yii::app()->request->getServerName()."/JobFair/images/imgs/cancel_normal.png\'" >
				<center>
					<br><br style="font-size:8px;">cancel
				</center>
			</div>		

			<body style="padding-left:10px;">
			<h1 style="font-size:18px;">&nbsp' . $local_user_name . '\'s Documents</h1>
			<HR color="#CDCDCD" WIDTH="90%" NOSHADE>
			<br>
			<!-- <div id="left-menu" style="width:25%; float:left;"> -->
			';
// 			$this->widget('bootstrap.widgets.TbButton', array(
// 			'label'=>'Open Document',
// 			'type'=>'primary', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
// 			'size'=>'null', // null, 'large', 'small' or 'mini'
// 			'block'=>true,
// 			'htmlOptions'=>array('id' => 'open_selected_document'),
// 			));
// 			$this->widget('bootstrap.widgets.TbButton', array(
// 			'label'=>'Import Document',
// 			'type'=>'primary', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
// 			'size'=>'null', // null, 'large', 'small' or 'mini'
// 			'block'=>true,
// 			'htmlOptions'=>array('id' => 'import_selected_document'),
// 			));	
// 			$this->widget('bootstrap.widgets.TbButton', array(
// 			'label'=>'Export Document',
// 			'type'=>'primary', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
// 			'size'=>'null', // null, 'large', 'small' or 'mini'
// 			'block'=>true,
// 			'htmlOptions'=>array('id' => 'export_selected_document'),
// 			));						
// 			$this->widget('bootstrap.widgets.TbButton', array(
// 			'label'=>'Delete Document',
// 			'type'=>'primary', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
// 			'size'=>'null', // null, 'large', 'small' or 'mini'
// 			'block'=>true,
// 			'htmlOptions'=>array('id' => 'delete_selected_document'),
// 			));
// 			$this->widget('bootstrap.widgets.TbButton', array(
// 			'label'=>'Rename Document',
// 			'type'=>'primary', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
// 			'size'=>'null', // null, 'large', 'small' or 'mini'
// 			'block'=>true,
// 			'htmlOptions'=>array('id' => 'rename_selected_document'),
// 			));
// 			$this->widget('bootstrap.widgets.TbButton', array(
// 			'label'=>'Cancel',
// 			'type'=>'primary', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
// 			'size'=>'null', // null, 'large', 'small' or 'mini'
// 			'block'=>true,
// 			'htmlOptions'=>array('id' => 'cancel'),
// 			));
			
			echo "</div>";
			echo '<div id="right-menu" style="width:75%; float:left; overflow:auto;">';
			echo "<ul class='nav nav-list'>";

			// echo $local_user_name;
			// echo '<br>user id: ' . $model->id . '<br>';

			if ($handle = opendir('/var/www/html/JobFair/userHomes/' . $local_user_name . '/saveFiles')) {
				while (false !== ($entry = readdir($handle))) {
					if ($entry != "." && $entry != ".." && substr($entry, -3) != "php") {

						$docId = substr($entry, 0, -4);
						$document = UserDocument::model()->findByAttributes(array('owner_id'=>$model->id, 'document_id'=>$docId));
						if ($document){
							$real_document_name = $document->document_name;
							//echo "<li> <a href='#' id='$entry' onclick='setSelected(\"$entry\")'> <i class='icon-file'> </i> "; echo " $real_document_name</a> </li> \n <br>";
							echo "<li style=\"list-style-type: none;background: url('http://'.Yii::app()->request->getServerName().'/JobFair/images/imgs/doc.jpg') no-repeat top left;height: 32px;padding-left: 48px;padding-top: 4px;\"> <a href='#' id='$entry' onclick='setSelected(\"$entry\")'>  "; echo " $real_document_name</a> </li> \n <br>";
						}
					}
				}
				if(!$document) {
							echo '
								<div class="alert">
								<button type="button" class="close" data-dismiss="alert">&times;</button>
								<strong>Warning!</strong> You have no documents.
								</div>
							';
						}
			closedir($handle);
			}
			echo "</ul>";
			echo "</div>";
		//$this->render('listDocument');
	}

	public function actionOpenDocument($rU, $dI)
	{

		// common variables
		$documentId = $dI;
		
		// local user variables
		$local_user_name = Yii::app()->user->name;
		$model = User::model()->find("username=:username",array(':username'=>$local_user_name));
		$local_user_id = $model->id;
		$local_user_real_name = $model->first_name . ' ' . $model->last_name;
		$mode = 'collabedit';
		
		//remote user varaibles
		$remote_user_name = $rU;
		$rmodel = User::model()->find("username=:username",array(':username'=>$remote_user_name));
		$remote_user_id = $rmodel->id;
		$remote_user_real_name = $rmodel->first_name . ' ' . $rmodel->last_name;
		$rmode = 'normaledit';

		// make a call to Zoho API on behalf of local user
		$local_user_url_arr = $this->openExistingEditor($local_user_name, $local_user_real_name, $documentId, $mode, NULL);
		$keys = array_keys($local_user_url_arr);
		$ownerUrl = $local_user_url_arr[$keys[0]];
		$remote_user_doc_id = $local_user_url_arr[$keys[1]];

		//echo json_encode($local_user_url_arr);
 		//echo "<br><strong>Method:actionOpenDocument()</strong><br> $ownerUrl <br> $remote_user_doc_id<br><br>";
		
		//	make a call to Zoho API on behalf of remote	user
		//											1st parameter MUST be local
		$remote_user_url_arr = $this->createNewEditor($local_user_name, $remote_user_real_name, $documentId, $rmode, $remote_user_doc_id);
		$keys = array_keys($remote_user_url_arr);
		$viewerUrl = $remote_user_url_arr[$keys[0]];
		
//		echo json_encode($remote_user_url_arr);		/*pragma ok*/
// 				
		$dmodel = UserDocument::model()->find("document_id=:document_id",array(':document_id'=>$documentId));

		$dmodel->owner_url = $ownerUrl;
		$dmodel->viewer_url = $viewerUrl;
		$dmodel->save();

// 		// clear all active documents	
// 		$this->markDocumentsInactive();
// 		
 		$ownerUrl = str_replace("\n", '', $ownerUrl);
 		//$ownerUrl = str_replace("\r", '', $ownerUrl);
 		$viewerUrl = str_replace("\n", '', $viewerUrl);
 		//$viewerUrl = str_replace("\r", '', $viewerUrl);
		
		$reponse = array(
			'local_user_url' => $ownerUrl,
			'remote_user_url' => $viewerUrl
			);
		
		//echo urldecode(json_encode($reponse));		//debug only
		echo json_encode($reponse);
	}

	public function actionRenameDocument($document, $newName)
	{
		// local user variables
		$local_user_name = Yii::app()->user->name;
		$model = User::model()->find("username=:username",array(':username'=>$local_user_name));
		$local_user_id = $model->id;
		$local_user_real_name = $model->first_name . ' ' . $model->last_name;
		
		$user=$local_user_name;
		$homeFolder = '/var/www/html/JobFair/userHomes';
		$userHomeFolder = $homeFolder . "/" . $user;
		$userSaveFiles = $userHomeFolder . "/saveFiles";
		
// 		$docId="";
// 		if ( (strpos($document, '.doc') == TRUE) ){
			$docId = substr($document, 0, -4);
		// 	echo 'it had a doc extension';
// 		} else {
// 			$docId = $document
// 		}
		
		$condition = 'owner_id = ' . $local_user_id;
		// this is clearing all active user documents
		echo "docId = ".$docId;
		echo "<br>";
		echo "document = ".$document;
		
		$rename = UserDocument::model()->find('document_id=:document_id AND owner_id=:owner_id',
		array(
		  ':document_id'=>$docId,
		  ':owner_id'=>$local_user_id,
		));
		$rename->document_name=$newName;
		$rename->save(false);
		//$this->render('renameDocument');
	}

	public function actionSaveDocument()
	{
		$this->render('saveDocument');
	}

	public function actionShareDocument()
	{
		$this->render('shareDocument');
	}

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}