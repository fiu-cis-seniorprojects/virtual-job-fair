<!-- 
 __  __             __                      ___      
/\ \/\ \  __       /\ \__                  /\_ \     
\ \ \ \ \/\_\  _ __\ \ ,_\  __  __     __  \//\ \    
 \ \ \ \ \/\ \/\`'__\ \ \/ /\ \/\ \  /'__`\  \ \ \   
  \ \ \_/ \ \ \ \ \/ \ \ \_\ \ \_\ \/\ \L\.\_ \_\ \_ 
   \ `\___/\ \_\ \_\  \ \__\\ \____/\ \__/.\_\/\____\
    `\/__/  \/_/\/_/   \/__/ \/___/  \/__/\/_/\/____/
                                                     
                                                     
 _____          __          ____                      
/\___ \        /\ \        /\  _`\         __         
\/__/\ \    ___\ \ \____   \ \ \L\_\ __   /\_\  _ __  
   _\ \ \  / __`\ \ '__`\   \ \  _\/'__`\ \/\ \/\`'__\
  /\ \_\ \/\ \L\ \ \ \L\ \   \ \ \/\ \L\.\_\ \ \ \ \/ 
  \ \____/\ \____/\ \_,__/    \ \_\ \__/.\_\\ \_\ \_\ 
   \/___/  \/___/  \/___/      \/_/\/__/\/_/ \/_/\/_/ 
												Luis B.
 -->
<html>
 <head>
  <link rel="stylesheet" type="text/css" href="http://<?php echo Yii::app()->request->getServerName()?>/JobFair/css/FiuCsAuth.css">
  <link rel="stylesheet" type="text/css" href="http://<?php echo Yii::app()->request->getServerName()?>/JobFair/bootstrap/css/bootstrap.css">
 </head>
 <body>
  <div class="header-bar">
   <center><img class="top_image" src="http://<?php echo Yii::app()->request->getServerName()?>/JobFair/images/imgs/fiu.jpg" alt=""><center>
   <h2><small>Fiu Seniors sign in here</small></h2>
  </div>
 <form method="post" action="">
  <fieldset>
   <div class="box">
    <label>Panther Email</label>
    <input type="text" name="panthermail" placeholder="example001@fiu.edu">
    <label>Panther ID</label>
    <input type="text" name="pantherid" placeholder="xxxxxxx">
    <span class="help-block">type in your credentials to login</span>
    <button type="submit" class="btn btn-block" name="submit" value="Submit">Submit</button>
   </div>
  </fieldset>
 </form>
 </body>
</html>
<?php

// ------------------------- DEBUG ONLY REMOVE FOR PRODUCTION -------------------------
ini_set('display_errors', 'On');
error_reporting(E_ALL);
// ------------------------- DEBUG ONLY REMOVE FOR PRODUCTION -------------------------

if(isset($_POST['submit']))
{
    $panthermail = $_REQUEST['panthermail'];
    $pantherid = $_REQUEST['pantherid'];
}

/** Description of FiuCsAuth 
 *
 * @author Luis Benjumea the FiuCsAuth class contains the logic necessary to authenticate
 *		   a user against the Senior Project Website API by using the users' FIU Computer Science
 *		   Senior Proyect credentials, all login in this file is implemented by the author.
 * 
 */
class FiuCsAuth
{
    // declare CONSTANTS
    private $BASEURL = 'http://spws-dev.cis.fiu.edu';
    private $PORT = ':8080';
    private $PATHURL = '/SPW2-RegisterAPI/rest/SPWRegister/getUserInfo/';
    private $TOKEN =  '123FIUspw/';
    
    // declare variables
   	private $userData = array('email'=>'null','id'=>'null','first_name'=>'null','last_name'=>'null','middle'=>'null','valid'=>'null');
	private $email;
	private $pantherId;
	
	/**
	 * calls internal private function assertServer() to ensure API server is up.
	 * this is a guard against SPW not being ready. 
	 *
	 * @return returns True if the server is up, False otherwise.
	 */
	public function getServerStatus(){
    	return $this->assertServer();
    }
    
	/*
		This function is required because the Senior Proyect Website guys who are providing the API for authentication are as of this point being incredibly vague and undecisive on their implementation, Saying that they may even change things ( like the url and port number I'll bet... )
		
		curious eyes will want to refer to their: SPWv2RestAPI_Specifications.pdf document, page 8
		relevant excerpt:
		
		" ...Server address and port: The server address and port where the REST API is deployed. Now it is deployed at http://srprog-spr13-01.aul.fiu.edu:8080 this could also change once the service is on production environment; developers must make this part configurable on their apps. ..."
		
		<sarcasm> GREAT! </sarcasm>
		
	*/
    private function assertServer(){
		$file = $this->BASEURL. $this->PORT;
		$file_headers = @get_headers($file);
		if($file_headers[0] == 'HTTP/1.1 404 Not Found') {
			return false;
		}
		return true;
	}

	/**
	 * debugging function, internal use only.
	 */
	public function debug($msg){
		echo "\n<br>***** begin debug*****\n<br> $msg \n<br>***** end debug*****";
	}

	/**
	 * debugging function, internal use only.
	 */
    public function displayConst() {
        echo "$this->BASEURL\n<br>";
        echo "$this->PORT\n<br>";
        echo "$this->PATHURL\n<br>";
        echo "$this->TOKEN\n<br>";
    }
    
    /**
	 * calls internal private function authenticateUser() returns information on if the user is valid, an FIU CS Senior, and if he/she is, attempts to authenticate them.
	 *
	 * @return True, if the user is valid, False otherwise.
	 */
    public function isUserValid($pm, $pid){
    	if ($pm == NULL || $pid == NULL){
    		return false;
    	}
    	return $this->authenticateUser($pm, $pid);
    }
    
    /**
	 * gets user information from SPW API, parses the data and validates the credentials the user provided against that information and returns a status representing the validity of the user 
	 *
	 * @return True, if the user is valid, False otherwise.
	 */
    private function authenticateUser($pm, $pid){
    	$this->email = $pm;
    	$this->pantherId = $pid;
    	$status = false;
    	$this->keys = array_keys($this->userData);
    	//URL of SPW  
		$url = $this->BASEURL. $this->PORT . $this->PATHURL . $this->TOKEN . $this->email; 
		$ch = curl_init();  

		// set URL and other appropriate options  
		curl_setopt($ch, CURLOPT_URL, $url);  
		curl_setopt($ch, CURLOPT_HEADER, 0);  
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  

		// grab URL and pass it to the browser  

		$responseStr = curl_exec($ch);  

		// close curl resource, and free up system resources  
		curl_close($ch);
		
		// cleanup response string
		$responseStr=str_replace("{","",$responseStr);
		$responseStr=str_replace("}","",$responseStr);
		$responseStr=str_replace("\"","",$responseStr);

		// tokenize
		$tok = explode(",",$responseStr);
		$isEmailValid = explode(":",$tok[5]);
		$actPantherId = explode(":",$tok[1]);

		// parse data if valid
		if ( (strcasecmp($isEmailValid[1], "true") == 0) && $this->pantherId == $actPantherId[1] ){
			$status = true;
			for ($x=0; $x<=5; $x++) {
				$ltok=explode(":",$tok[$x]);
				$this->userData[$this->keys[$x]] = $ltok[1];
			}
		}
		return $status;	 
    }
    
    /**
	 * returns an associative array filled with the user information available.
	 * 
	 * @return returns the array.
	 */
  	public function getUserInfo(){

       return $this->userData;
    }
}
?>