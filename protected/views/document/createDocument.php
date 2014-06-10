<?php
/* @var $this DocumentController */

$this->breadcrumbs=array(
	'Document'=>array('/document'),
	'CreateDocument',
);
?>
<h1><?php echo $this->id . '/' . $this->action->id; ?></h1>

<p>
	You may change the content of this page by modifying
	the file <tt><?php echo __FILE__; ?></tt>.
</p>

<?php 

//extract data from the post
extract($_POST);

$url = 'https://exportwriter.zoho.com/remotedoc.im';

//set POST variables
$apikey='835dc233a4276528c2630262da701051';
$output='url';
$mode='collabedit';
$filename='docA.doc';
$lang='en';
$id='9864654684658468846';
$format='doc';
$realUserName='Pepito Mendieta';
$saveurl='http://'.Yii::app()->request->getServerName().'/lcb-text-editor-test/Zoho/ownersave.php';
$fields_string='';
$headers = array("Content-Type: multipart/form-data");

/*
From the curl_setopt reference: 
" Note:
Passing an array to CURLOPT_POSTFIELDS will encode the data as multipart/form-data"
*/
$fields = array(
						//'url' => urlencode($url),
						'apikey' => urlencode($apikey),
						'output' => urlencode($output),
						'mode' => urlencode($mode),
						'filename' => urlencode($filename),
						'lang' => urlencode($lang),
						'id' => urlencode($id),
						'format' => urlencode($format),
						'username' => urlencode($realUserName),
						'saveurl' => urlencode($saveurl)
				);

//url-ify the data for the POST
foreach($fields as $key=>$value) { 
	$fields_string .= $key.'='.$value.'&'; 
}
	
rtrim($fields_string, '&');

//echo "here's the string sent: \n<br> $fields_string \n<br>";

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
echo "\n<br> result is: $result \n<br>\n<br>";
//close connection
curl_close($ch);

		// tokenize
		$tok = explode("WARNING=",$decode);
		print_r($tok[0]);
		$ownerUrl = trim($tok[0], "URL=");
		$remoteUrl = explode("DOCUMENTID=",$str);
		print_r($remoteUrl[1]);
		//echo "owner url is: " . $ownerUrl;
		//echo ""\n<br>;
		//echo "remote url is: " . $remoteUrl;
//$this->redirect($ownerUrl);

?>