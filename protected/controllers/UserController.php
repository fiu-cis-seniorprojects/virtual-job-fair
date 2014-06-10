<?php

require ('PasswordHash.php');

class UserController extends Controller
{
	public $username = '';
	public $email = '';
	
	public function actionIndex()
	{
		$user = User::model()->findByPk(1);
		$this->username = $user->username;
		$this->email = $user->email;
		
		$this->render('index', array('username'=>$this->username, 'email'=>$this->email));
	}
	
	public function actionRegister()
	{
		$this->render('register');
	}
	
	public function actionChangePassword() {
		$model = User::getCurrentUser();
		$error = '';
		if(isset($_POST['User'])) {
			$pass = $_POST['User']['password'];
			$p1 = $_POST['User']['password1'];
			$p2 = $_POST['User']['password2'];
			//verify old password
			$username = Yii::app()->user->name;
			$hasher = new PasswordHash(8, false);
			$login = new LoginForm;
			$login->username = $username;
			$login->password = $pass;

			//$user = User::model()->find("username=:username AND password=:password", array(":username"=> $username, ":password"=>$password));
			if (!$login->validate()){
				$error = "Old Password was incorrect.";
				$this->render('ChangePassword',array('model'=>$model, 'error' => $error));
			} elseif ($p1 == $p2) {
				//Hash the password before storing it into the database
				$hasher = new PasswordHash(8, false);
				$user = User::getCurrentUser();
				$user->password = $hasher->HashPassword($p1);
				$user->save(false);
				$this->redirect("/JobFair/index.php/profile/view");
			} else {
				$error = "Passwords do not match.";
				$this->render('ChangePassword',array('model'=>$model, 'error' => $error));
			}
		} else {
			$this->render('ChangePassword',array('model'=>$model, 'error' => $error));
		}
	}
	
	public function mynl2br($text) {
		return strtr($text, array("\r\n" => '<br />', "\r" => '<br />', "\n" => '<br />'));
	}

	public function actionEmployerRegister()
	{
		$model=new User;
	
		// uncomment the following code to enable ajax-based validation
		/*
		 if(isset($_POST['ajax']) && $_POST['ajax']==='user-EmployerRegister-form')
		 {
		echo CActiveForm::validate($model);
		Yii::app()->end();
		}
		*/

		
		if(isset($_POST['User']))
		{
			$model->attributes=$_POST['User'];
			//print "<pre>";print_r($model);print "</pre>";return;
			if($model->validate())
			{
				if ($this->actionVerifyEmployerRegistration() != "") {
					$this->render('EmployerRegister');
				}
				//Form inputs are valid
				//Populate user attributes
				$model->FK_usertype = 2;
				$model->registration_date = new CDbExpression('NOW()');
				$model->activation_string = $this->genRandomString(10);
				$model->image_url = '/JobFair/images/profileimages/avatarsmall.gif';
					
				//Hash the password before storing it into the database
				$hasher = new PasswordHash(8, false);
				$model->password = $hasher->HashPassword($model->password);
	
				//Save user into database. Account still needs to be activated
					
				//save employers company info
				if ($model->save($runValidation=false)) {
					$companyInfo = new CompanyInfo;
					$companyInfo->attributes = $_POST['CompanyInfo'];
					$companyInfo->description = $this->mynl2br($_POST['CompanyInfo']['description']);
					$companyInfo->FK_userid = $model->id;
					$companyInfo->save($runValidation=false);
					$basicInfo = new BasicInfo;
					$basicInfo->attributes = $_POST['BasicInfo'];
					$basicInfo->about_me = $this->mynl2br($_POST['BasicInfo']['about_me']);
					$basicInfo->userid = $model->id;
					$basicInfo->city = $companyInfo->city;
					$basicInfo->state = $companyInfo->state;
					
					$basicInfo->save(false);
				}
				
				
				$link = 'http://'.Yii::app()->request->getServerName().'/JobFair/index.php/profile/employer/user/'.$model->username;
				$link2 =  'http://'.Yii::app()->request->getServerName().'/JobFair/index.php/profile/employer/user/'.$model->username;
				$message = $model->username." just joined VJF, click here to view their profile.";
				User::sendAllStudentVerificationAlart($model->id, $model->username, $model->email, $message, $link);
				$message1 = "There is a new employer named ".$model->username. " that is waiting for acctivation"; 
				$admins = User::model()->findAllByAttributes(array('FK_usertype'=>3));
				User::sendAdminNotificationNewEmpolyer($model, $admins, $link2, $message1);
                $message = "You have successfully registered. Once your account has been approved, you will receive an email stating your account is active.";
                $message .= "<br/>Your username: $model->username";
                User::sendEmail($model->email, "Registration Notification", "Registration Notification", $message);
				$this->render('NewEmployer');
				return;	
			}
		}
		$this->render('EmployerRegister',array('model'=>$model));
	}
	
	public function actionNewEmployer(){
		$this->render('NewEmployer');
	}
	
 	public function actionSendVerificationEmail($email = null){
		
		if (!isset($email)) {
			$username = $_GET['username'];
			$user = User::model()->find("username=:username",array(':username'=>$username));
		} else {
			$user = User::model()->find("email=:email",array(':email'=>$email));
		}
		
		$user->sendVerificationEmail();
		$this->redirect('/JobFair/index.php/site/page?view=verification');
	}
	
	public function actionStudentRegister()
	{
		$model=new User;
	
		// uncomment the following code to enable ajax-based validation
		/*
		 if(isset($_POST['ajax']) && $_POST['ajax']==='user-StudentRegister-form')
		 {
		echo CActiveForm::validate($model);
		Yii::app()->end();
		}
		*/
	
		if(isset($_POST['User']))
		{
			if ($this->actionVerifyStudentRegistration() != "") {
				$this->render('StudentRegister');
			}
						
			$model->attributes=$_POST['User'];
			$model->image_url = '/JobFair/images/profileimages/avatarsmall.gif';
			$resume = Resume::model();
			
			//Form inputs are valid

			// save ID to resume table
			$resume->id = $model->id;
			$resume->save(false);

			//Populate user attributes
			$model->FK_usertype = 1;
			$model->registration_date = new CDbExpression('NOW()');
			$model->activation_string = $this->genRandomString(10);

			//Hash the password before storing it into the database
			$hasher = new PasswordHash(8, false);
			$model->password = $hasher->HashPassword($model->password);

			//Save user into database. Account still needs to be activated
			$model->save($runValidation=false);
			
			//added in order to store phone number
			$basicInfo = new BasicInfo;
			$basicInfo->attributes = $_POST['BasicInfo'];
			$basicInfo->userid = $model->id;
			
			if(!isset($_POST['BasicInfo']['phone']))
			{
				Yii::log("checks", CLogger::LEVEL_ERROR, 'application.controller.Prof');
				$basicInfo->phone = NULL;
			}
				
			
			$basicInfo->save(false);

			//Send the verification email
			//$model->sendVerificationEmail();

			$this->actionSendVerificationEmail($model->email);
			return;
			
		}
		$error = '';
		$this->render('StudentRegister',array('model'=>$model, 'error' => $error));
	}
	
	
	public function actionVerifyStudentRegistration(){
		$user = $_POST['User'];
		$error = "";
		
		$username = $user['username'];
		$password = $user['password'];
		$password2 = $user['password_repeat'];
		$email = $user['email'];

		
		if ((strlen($username) < 4) || (!ctype_alnum($username))) {
			$error .= "Username must be alphanumeric and at least 4 characters.<br />";
		}
		if (User::model()->find("username=:username",array(':username'=>$username))) {
			$error .= "Username is taken<br />";
		}
		if (User::model()->find("email=:email",array(':email'=>$email))) {
			$error .= "Email is taken<br />";
		}
		if ($password != $password2) {
			$error .= "Passwords do not match<br />";
		}
		if (strlen($password) < 6) {
			$error .= "Password must be more than 5 characters<br />";
		}
		if (!$this->check_email_address($email)){
			$error .= "Email is not correct format<br />";
		}
		
		print $error;
		return $error;
	}
	
	public function actionVerifyEmployerRegistration(){
		$user = $_POST['User'];
		$company = $_POST['CompanyInfo'];
		$basicInfo = $_POST['BasicInfo'];
		$error = "";
		
		$username = $user['username'];
		$password = $user['password'];
		$password2 = $user['password_repeat'];
		$email = $user['email'];
		
		$aboutme = $basicInfo['about_me'];
		$phone = $basicInfo['phone'];
		
		$companyname = $company['name'];
		$companystreet = $company['street'];
		$companystreet2 = $company['street2'];
		$companycity = $company['city'];
		$companystate = $company['state'];
		$companyzip = $company['zipcode'];
		$companydescription = $company['description'];
		
		if ((strlen($username) < 4) || (!ctype_alnum($username))) {
			$error .= "Username must me alphanumeric and at least 4 characters.<br />";
		}
		if (User::model()->find("username=:username",array(':username'=>$username))) {
			$error .= "Username is taken<br />";
		}
		if (User::model()->find("email=:email",array(':email'=>$email))) {
			$error .= "Email is taken<br />";
		}
		if ($password != $password2) {
			$error .= "Passwords do not match<br />";
		}
		if (strlen($password) < 6) {
			$error .= "Password must be more than 5 characters<br />";
		}
		
		if (!$this->check_email_address($email)){
			$error .= "Email is not correct format<br />";
		}
		if (strlen($aboutme) < 1) {
			$error .= "Must enter information for \"About Me\"<br />";
		}
		if (strlen($companyname) < 1) {
			$error .= "Must enter information for \"Company Name\"<br />";
		}
		if (strlen($companystreet) < 1) {
			$error .= "Must enter information for \"Company Street\"<br />";
		}
		if (strlen($companycity) < 1) {
			$error .= "Must enter information for \"Company City\"<br />";
		}
		if (strlen($companystate) < 1) {
			$error .= "Must enter information for \"Company State\"<br />";
		}
		if (strlen($companyzip) < 1) {
			$error .= "Must enter information for \"Company Zip\"<br />";
		}
		if (strlen($companydescription) < 1) {
			$error .= "Must enter information for \"Company Description\"<br />";
		}
		
		print $error;
		//return ($error == "");
		return $error;
	}
	
	function check_email_address($email) {
		// First, we check that there's one @ symbol, and that the lengths are right
		if (!preg_match("/^[^@]{1,64}@[^@]{1,255}$/", $email)) {
			// Email invalid because wrong number of characters in one section, or wrong number of @ symbols.
			return false;
		}
		// Split it into sections to make life easier
		$email_array = explode("@", $email);
		$local_array = explode(".", $email_array[0]);
		for ($i = 0; $i < sizeof($local_array); $i++) {
			if (!preg_match("/^(([A-Za-z0-9!#$%&'*+\/=?^_`{|}~-][A-Za-z0-9!#$%&'*+\/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$/", $local_array[$i])) {
				return false;
			}
		}
		if (!preg_match("/^\[?[0-9\.]+\]?$/", $email_array[1])) { // Check if domain is IP. If not, it should be valid domain name
			$domain_array = explode(".", $email_array[1]);
			if (sizeof($domain_array) < 2) {
				return false; // Not enough parts to domain
			}
			for ($i = 0; $i < sizeof($domain_array); $i++) {
				if (!preg_match("/^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$/", $domain_array[$i])) {
					return false;
				}
			}
		}
	
		return true;
	}
	
	public function actionSendVerification() {
		
	}
	
	
	
	public static function genRandomString($length = 10) {
		$characters = "0123456789abcdefghijklmnopqrstuvwxyz";
		$string = "";
		for ($p = 0; $p < $length; $p++) {
			$string .= $characters[mt_rand(0, strlen($characters) - 1)];
		}
	
		return $string;
	}

	
	public function actionMessage()
	{
		
			$username = Yii::app()->user->name;
			$user = User::model()->find("username=:username",array(':username'=>$username));
			$this->render('message', array('user'=>$user));
	}	
		
		
	public function actionTomer()
	{
		$this->render('message');
	}
	
	public function actionVerifyEmail($username, $activation_string)
	{
		$usermodel = User::model()->find("username=:username AND activation_string=:activation_string",array(':username'=>$username, ':activation_string'=>$activation_string));
		if ($usermodel != null)
		{
			$usermodel->activated = 1;
			$usermodel->save(false);
			$this->redirect("/JobFair/index.php/site/login");
		}
		else
			redirect();
	}
	
	
	public function actionRegisterLinkedIn()
	{
		// if user canceled, redirect to home page
		if (isset($_GET['oauth_problem'])){
			$problem = $_GET['oauth_problem'];
			if ($problem == 'user_refused')
				$this->redirect('/JobFair/index.php');
		}

		if (!isset($_SESSION))
			session_start();

        //edit by Manuel making the link dynamic, using Yii
		$config['base_url']             =   'http://'.Yii::app()->request->getServerName().'/JobFair/index.php/user/auth1.php';
		$config['callback_url']         =   'http://'.Yii::app()->request->getServerName().'/JobFair/index.php/user/RegisterLinkedIn';
		$config['linkedin_access']      =   '2rtmn93gu2m4';
		$config['linkedin_secret']      =   'JV0fYG9ls3rclP8v';

		include_once Yii::app()->basePath."/views/user/linkedin.php";

		# First step is to initialize with your consumer key and secret. We'll use an out-of-band oauth_callback
		$linkedin = new LinkedIn($config['linkedin_access'], $config['linkedin_secret'], $config['callback_url'] );
		//$linkedin->debug = true;

		if (isset($_REQUEST['oauth_verifier'])){
			$_SESSION['oauth_verifier']     = $_REQUEST['oauth_verifier'];

			$linkedin->request_token    =   unserialize($_SESSION['requestToken']);
			$linkedin->oauth_verifier   =   $_SESSION['oauth_verifier'];
			$linkedin->getAccessToken($_REQUEST['oauth_verifier']);

			$_SESSION['oauth_access_token'] = serialize($linkedin->access_token);
			header("Location: " . $config['callback_url']);
			exit;
		}
		else{
			$linkedin->request_token    =   unserialize($_SESSION['requestToken']);
			$linkedin->oauth_verifier   =   $_SESSION['oauth_verifier'];
			$linkedin->access_token     =   unserialize($_SESSION['oauth_access_token']);
		}

		# You now have a $linkedin->access_token and can make calls on behalf of the current member
		$xml_response = $linkedin->getProfile("~:(id,first-name,last-name,headline,picture-url,industry,email-address,languages,phone-numbers,skills,educations,location:(name),positions,picture-urls::(original))");
		$data = simplexml_load_string($xml_response);

		// get user by linkedinid
		$model = new User();
		$user = User::model()->findByAttributes(array('linkedinid'=>$data->id));
		
		
		// check if user exits in database, if so login
		if ($user != null) {
			
			if ($user->disable != 1)
			{
				$identity = new UserIdentity($user->username, '');
				if ($identity->authenticateOutside()) {
					Yii::app()->user->login($identity);
				}
				
				$this->redirect("/JobFair/index.php/home/studenthome");
				return;
			} else {
				$this->redirect("/JobFair/index.php/site/page?view=disableUser");
				return;
			}
			
			
		// register
		} else {
			
// 			print "<pre>"; print_r('user is null');print "</pre>";

			// check that there is no duplicate user
			$duplicateUser = User::model()->findByAttributes(array('email'=>$data->{'email-address'}));
			if ($duplicateUser != null) {
				$error = 'User email is already linked with another account.';
				$model=new User;
				$this->render('StudentRegister',array('error' => $error, 'model'=>$model));
				return;
			}

			// Populate user attributes
			$model->FK_usertype = 1;
			$model->registration_date = new CDbExpression('NOW()');
			$model->activation_string = 'linkedin';
			$model->username = $data->{'email-address'}[0];
			$model->first_name = $data->{'first-name'};
			$model->last_name = $data->{'last-name'};
			$model->email = $data->{'email-address'};
			$model->image_url = $data->{'picture-urls'}->{'picture-url'}[0];
			$model->linkedinid = $data->id;
			//Hash the password before storing it into the database
			$hasher = new PasswordHash(8, false);
			$model->password = $hasher->HashPassword('tester');
			$model->activated = 1;
			$model->has_viewed_profile = 1;
			$model->save(false);

			// 		// ------------------BASIC INFO---------------
			$basic_info = null;
			$basic_info = BasicInfo::model()->findByAttributes(array('userid'=>$model->id));
			if ($basic_info == null)
				$basic_info = new BasicInfo();
			$basic_info->userid = $model->id;
			$basic_info->phone = $data->{'phone-numbers'}->{'phone-number'}->{'phone-number'};
			$basic_info->city = $data->location->name;
			$basic_info->state = '';
			$basic_info->about_me = $data->headline;

			$basic_info->save(false);
			// ------------------BASIC INFO -----------------

			// -----------------EDUCATION ----------------------
			// get number of educations to add
			$educ_count = $data->educations['total'];

			// delete current educations
			$delete_educs = Education::model()->findAllByAttributes(array('FK_user_id'=>$model->id));
			foreach ($delete_educs as $de)
			{
				$de->delete();
			}

			// add educations
			for ($i=0; $i<$educ_count; $i++)
			{
				// first check if current education is in school table. if not, add it
				$current_school_name = $data->educations->education[$i]->{'school-name'};
				$school_exists = School::model()->findByAttributes(array('name'=>$current_school_name));

				if ($school_exists == null){
					$new_school = new School();
					$new_school->name = $current_school_name;
					$new_school->save();
					$school_id = School::model()->findByAttributes(array('name'=>$current_school_name))->id;
				} else {
					$school_id = $school_exists->id;
				}

				// now ready to add new education
				$new_educ = new Education();
				$new_educ->degree = $data->educations->education[$i]->degree;
				$new_educ->major = $data->educations->education[$i]->{'field-of-study'};
				// 	   	$model->admission_date=date('Y-m-d',strtotime($model->admission_date));
				$new_educ->graduation_date = date('Y-m-d',strtotime($data->educations->education[$i]->{'end-date'}->year));
				// 	   	print "<pre>"; print_r($new_educ->graduation_date);print "</pre>";return;
				$new_educ->FK_school_id = $school_id;
				$new_educ->FK_user_id = $model->id;
				$new_educ->additional_info = $data->educations->education[$i]->notes;
				$new_educ->save(false);
			}
			// -----------------EDUCATION ----------------------

			// -----------------EXPERIENCE -------------------
			// get number of educations to add
			$pos_count = $data->positions['total'];

			// delete current positions
			$delete_pos = Experience::model()->findAllByAttributes(array('FK_userid'=>$model->id));
			foreach ($delete_pos as $de)
			{
				$de->delete();
			}


			for ($i=0; $i<$pos_count; $i++)
			{
				$new_pos = new Experience();
				$new_pos->FK_userid = $model->id;
				$new_pos->company_name = $data->positions->position[$i]->company->name;
				$new_pos->job_title = $data->positions->position[$i]->title;
				$new_pos->job_description = $data->positions->position[$i]->summary;
				$temp_start_date = $data->positions->position[$i]->{'start-date'}->month . '/01/' . $data->positions->position[$i]->{'start-date'}->year;
				$new_pos->startdate = date('Y-m-d', strtotime($temp_start_date));
				if ($data->positions->position[$i]->{'is-current'} == 'true'){
					$new_pos->enddate =  '';
				} else {
					$temp_end_date = $data->positions->position[$i]->{'end-date'}->month . '/01/' . $data->positions->position[$i]->{'end-date'}->year;
					$new_pos->enddate = date('Y-m-d', strtotime($temp_end_date));
				}
				$new_pos->city = '';
				$new_pos->state = '';
				$new_pos->save(false);
			}
			// -----------------EXPERIENCE -------------------

			// ----------------------SKILLS----------------------

			// get number of educations to add
			$linkedin_skill_count = $data->skills['total'];
			for ($i=0; $i<$linkedin_skill_count; $i++){
				// check if skill exists in skill set table, if not, add it to skill set table
				if (Skillset::model()->findByAttributes(array('name'=>$data->skills->skill[$i]->skill->name)) == null){
					$new_skill = new Skillset();
					$new_skill->name = $data->skills->skill[$i]->skill->name;
					$new_skill->save(false);
					echo 'New Skill ' . $new_skill->attributes;
				}

				// check if student has that skill, if not add it to student-skill-map table
				if (StudentSkillMap::model()->findByAttributes(array('userid'=>$model->id,
						'skillid'=>Skillset::model()->findByAttributes(array('name'=>$data->skills->skill[$i]->skill->name))->id)) == null){
						$new_sdnt_skill = new StudentSkillMap();
						$new_sdnt_skill->userid = $model->id;
						$new_sdnt_skill->skillid = Skillset::model()->findByAttributes(array('name'=>$data->skills->skill[$i]->skill->name))->id;
						$new_sdnt_skill->ordering = $i + 1;
						$new_sdnt_skill->save(false);
				}
			}
			// ----------------------SKILLS----------------------

			// LOGIN
			$user = User::model()->find("username=:username",array(':username'=>$model->username));
			$identity = new UserIdentity($user->username, 'tester');
			if ($identity->authenticate()) {
				Yii::app()->user->login($identity);
			}
			$this->redirect("/JobFair/index.php/user/ChangeFirstPassword");
		}

	}

	public function actionChangeFirstPassword() {

		$model = User::getCurrentUser();
		$error = '';
		if(isset($_POST['User'])) {
			$pass = 'tester';
			$p1 = $_POST['User']['password1'];
			$p2 = $_POST['User']['password2'];
			//verify old password
			$username = Yii::app()->user->name;
			$hasher = new PasswordHash(8, false);
			$login = new LoginForm;
			$login->username = $username;
			$login->password = $pass;

			if ($p1 == $p2) {
				//Hash the password before storing it into the database
				$hasher = new PasswordHash(8, false);
				$user = User::getCurrentUser();
				$user->password = $hasher->HashPassword($p1);
				$user->save(false);
				$this->redirect("/JobFair/index.php/home/studenthome");
			} else {
				$error = "Passwords do not match.";
				$this->render('ChangeFirstPassword',array('model'=>$model, 'error' => $error));
			}
		} else {
			$this->render('ChangeFirstPassword',array('model'=>$model, 'error' => $error));
		}
	}
	
	public function actionAuth1()
	{
		$this->render('auth1');
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
