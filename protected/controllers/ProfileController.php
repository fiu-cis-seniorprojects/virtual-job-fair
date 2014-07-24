<?php

class ProfileController extends Controller
{

	
	public function actionView()
	{
		
		if (isset($_GET['user'])){
			$username = $_GET['user'];			
		} else {
			$username = Yii::app()->user->name;
		}
		$user = User::model()->find("username=:username",array(':username'=>$username));

        $saveQ = SavedQuery::model()->findAll("FK_userid=:id", array(':id'=>$user->id));
		
		if ($user->FK_usertype == 2){
			$this->actionViewEmployer();
			return;	
			}
				
		//Get all schools
		$allSchools = School::getAllSchools();		
		
		// Get Resume
		$resume = Resume::model()->findByPk($user->id);
		$videoresume = VideoResume::model()->findByPk($user->id);
// 		print "<pre>"; print_r($videoresume->video_path);print "</pre>";return;
		
		$this->render('View', array('user'=>$user, 'allSchools'=>$allSchools, 'resume'=>$resume, 'videoresume'=>$videoresume, 'saveQ'=>$saveQ));
	}
	
	
	public function actionViewEmployer()
	{
		
		$username = Yii::app()->user->name;
		
		$user = User::model()->find("username=:username",array(':username'=>$username));
	    $this->render('ViewEmployer', array('user'=>$user)); 
	    	
	}
	
	
	public function actionVideoEmployer()
	{
	
		if (isset($_GET["notificationRead"]))
		{
			//print "<pre>"; print_r($_GET["notificationRead"]);print "</pre>";return;
			Notification::markHasBeenRead($_GET["notificationRead"]);
		}
		
		if (isset($_GET['user'])){
			$username = $_GET['user'];
		}
		$model = User::model()->find("username=:username",array(':username'=>$username));
		$this->render('videoemployer', array('user'=>$model,));
	}
	
	public function actionVideoStudent()
	{
	
		if (isset($_GET["notificationRead"]))
		{
			//print "<pre>"; print_r($_GET["notificationRead"]);print "</pre>";return;
			Notification::markHasBeenRead($_GET["notificationRead"]);
		}
	
		if (isset($_GET['user'])){
			$username = $_GET['user'];
		}
		$model = User::model()->find("username=:username",array(':username'=>$username));
		$this->render('videostudent', array('user'=>$model,));
	}

    public function actionSaveInterest()
    {
        //var_dump($_GET);die;
        $suc = false;
        $username = Yii::app()->user->name;
         if(isset($_GET['day']))
        {
            $date = $_GET['day'];
        }
        else{ $date = 0; }

        $model = User::model()->find("username=:username",array(':username'=>$username));
        $model->job_int_date = $date;
        $model->save(false);

        $savedQ = SavedQuery::model()->findAll("FK_userid=:id",array(':id'=>$model->id));
        foreach($savedQ as $sq)
        {
            if(isset($_GET[$sq->id]))
            {
                $sq->active = 1;
                $sq->save(false);
            }
            if(!isset($_GET[$sq->id]))
            {
                $sq->active = 0;
                $sq->save(false);
            }
        }
        $suc = true;
        $this->redirect('/JobFair/index.php/profile/view');
    }

    public function actionDeleteInterest()
    {
        $model = SavedQuery::model()->findByPk($_GET['id']);
        //var_dump($model);die;
        $model->delete();
        $this->redirect('/JobFair/index.php/profile/view');
    }
	
	public function actionSaveSkills(){
		$user = User::getCurrentUser();
		if (!isset($_POST['Skill'])){
			foreach($user->studentSkillMaps as $skill){
				$skill->delete();
			}
			$this->redirect("/JobFair/index.php/profile/view");
			return;
		}
		$skills = $_POST['Skill'];
		//first wipe out the users skills
		
	
		foreach($user->studentSkillMaps as $skill){
			$skill->delete();
		}
		$i = 1;
		foreach($skills as $skill){
			$skillmap = new StudentSkillMap;
			$skillmap->userid = $user->id;
			if (!ctype_digit($skill)) {
				//create a new skill
				$newskill = new Skillset;
				$newskill->name = $skill;
				$newskill->save(false);
				$skillmap->skillid = $newskill->id;
			} else {
				$skillmap->skillid = $skill;
			}
			
			$skillmap->ordering = $i;
			$skillmap->save(false);
			$i++;
		}
        $commandPath = Yii::app()->getBasePath() . DIRECTORY_SEPARATOR . 'commands';
        $runner = new CConsoleCommandRunner();
        $runner->addCommands($commandPath);
        $commandPath = Yii::getFrameworkPath() . DIRECTORY_SEPARATOR . 'cli' . DIRECTORY_SEPARATOR . 'commands';
        $runner->addCommands($commandPath);
        $args = array('yiic', 'jobmatch', "-u", $user->username, "-e", $user->email);
        ob_start();
        $runner->run($args);
        echo htmlentities(ob_get_clean(), null, Yii::app()->charset);
		$this->redirect("/JobFair/index.php/profile/view");
	}
	
	
	public function actionDeleteEducation()
	{
		$model = Education::model()->findByPk($_GET['id']);
		$model->delete();
		//$this->actionView();
		$this->redirect('/JobFair/index.php/profile/view');
	}
	
	public function actionAddEducation(){
		
		$username = Yii::app()->user->name;
		$model = User::model()->find("username=:username",array(':username'=>$username));
		$schoolID = School::model()->getSchoolId($_POST['Education']['name']);
		if($schoolID=="null"){
			
			$newSchool = new School;
			
			$newSchool->name = $_POST['Education']['name'];
			$newSchool->save(false);
			$schoolID =  School::model()->getSchoolId($_POST['Education']['name']);
		}
		
		$education = new Education;
		$education->attributes = $_POST['Education'];
		$education->FK_school_id = $schoolID;
		$education->FK_user_id = $model->id;
		$education->save(false);
		//$this->actionView();
		$this->redirect('/JobFair/index.php/profile/view');
		
	}
	
	public function actionDeleteExperience()
	{
		$model = Experience::model()->findByPk($_GET['id']);
		$model->delete();
		//$this->actionView();
		$this->redirect('/JobFair/index.php/profile/view');
	}
	
	public function actionAddExperience(){
	
		
		$expenddate = $_POST['Experience']['enddate'];
		
		if($expenddate==""){
			$expenddate = '0000-00-00 00:00:00';
		}
			
		$username = Yii::app()->user->name;
		$model = User::model()->find("username=:username",array(':username'=>$username));
		//$schoolID = School::model()->getSchoolId($_POST['Education']['name']);
		$experience = new Experience;
		$experience->attributes = $_POST['Experience'];
		$experience->enddate = $expenddate;
		$experience->FK_userid = $model->id;
		$experience->save(false);
		//$this->actionView();
		$this->redirect('/JobFair/index.php/profile/view');
		
		
	
	}
	
	public function actionUploadImage() {
		$username = Yii::app()->user->name;
		//Yii::log("the user name is".$username, CLogger::LEVEL_ERROR, 'application.controller.Prof');
		
		$model = User::model()->find("username=:username",array(':username'=>$username));
		$oldUrl = $model->image_url; // get current image URL for user
		
		
		// if there is an image already, update current image
		if (strlen($oldUrl) > 0 && strpos($oldUrl, 'licdn') === false) {
			$uploadedFile=CUploadedFile::getInstance($model,'image_url');
			
			//edited by jorge
			$newurl = "/JobFair/images/profileimages/".$model->username."avatar.".$uploadedFile->extensionName;
			
			///
			if ($uploadedFile != null) {
				$uploadedFile->saveAs(Yii::app()->basePath.'/../..'.$newurl);
				
				//jorge
				$model->image_url = $newurl;
				$model->save(false);
				//
			}
			// else insert new image
		} else {
			 
			// code to upload image

		
			$uploadedFile=CUploadedFile::getInstance($model,'image_url'); // image object
			$fileName = "/JobFair/images/profileimages/".$model->username."avatar.".$uploadedFile->extensionName;
			$model->image_url = $fileName;
			$model->save(false); // save path in database
		
			if ($uploadedFile != null) {
				//print "<pre>"; print_r($model->attributes);print "</pre>";return;
				$uploadedFile->saveAs(Yii::app()->basePath.'/../..'.$fileName); // upload image to server
			}
		}
			
		$user = User::model()->find("username=:username",array(':username'=>$username));
		$utype = $user->FK_usertype;
			if($utype==1){
			$this->redirect('/JobFair/index.php/profile/view');
			}
			if($utype==2){
				$this->redirect('/JobFair/index.php/profile/viewEmployer');
			}
			else{$this->actionView();}
	}
	
	public function actionUploadVideo() {
		
		$username = Yii::app()->user->name;
		$model = User::model()->find("username=:username",array(':username'=>$username));
		$localvideo = VideoResume::model()->findByAttributes(array('id'=>$model->id));
		
// 		print "<pre>"; print_r('Hello');print "</pre>";return;
		
		if (isset($localvideo)) {
			$oldUrl = $localvideo->video_path;
		}
		
		if (isset($oldUrl)) {
			$uploadedFile=CUploadedFile::getInstance($localvideo,'videoresume');
			if (isset( $uploadedFile)) {
				$uploadedFile->saveAs(Yii::app()->basePath.'/../..'.$oldUrl);
			}
			
			// else insert new image
		} else {
			$localvideo = new VideoResume();
			// code to upload image
			$rnd = $model->id;
			$uploadedFile=CUploadedFile::getInstance($localvideo,'videoresume'); // image object
			$fileName = "v{$rnd}-{$uploadedFile}";  // random number + file name
			$localvideo->video_path = '/JobFair/resumes/'.$fileName;
			$localvideo->id = $model->id;
			if($localvideo->validate(array('video_path'))){
				$localvideo->save(false); // save path in database
			
				if (isset( $uploadedFile)) {
					//print "<pre>"; print_r($model->attributes);print "</pre>";return;
					$uploadedFile->saveAs(Yii::app()->basePath.'/../resumes/'.$fileName); // upload image to server
				}
			}
		}
		$this->actionView();
		
	}
	
	public function actionUploadResume() {
		
		$username = Yii::app()->user->name;
		$model = User::model()->find("username=:username",array(':username'=>$username));
		$localresume = Resume::model()->findByAttributes(array('id'=>$model->id));
		

		if (isset($localresume)) {
			$oldUrl = $localresume->resume;
		}
		
		if (isset($oldUrl)) {
			$uploadedFile=CUploadedFile::getInstance($localresume,'resume');
			$localresume->resume = $oldUrl;
			if($localresume->validate(array('resume'))){
				$localresume->save(false); // save path in database
				if (isset( $uploadedFile)) {
					$uploadedFile->saveAs(Yii::app()->basePath.'/../..'.$oldUrl);
				}
			}
			// else insert new image
		} else {
			$localresume = new Resume;
			// code to upload image
			$rnd = $model->id;
			$uploadedFile=CUploadedFile::getInstance($localresume,'resume'); // image object
			$fileName = "{$rnd}-{$uploadedFile}";  // random number + file name
			$localresume->resume = '/JobFair/resumes/'.$fileName;
			$localresume->id = $model->id;
			if($localresume->validate(array('resume'))){
				$localresume->save(false); // save path in database
			
				if (isset( $uploadedFile)) {
					
					$uploadedFile->saveAs(Yii::app()->basePath.'/../resumes/'.$fileName); // upload image to server
				}
			}
		}
		$this->actionView();
		
	}
	
	public function actionEditBasicInfo()
	{
		
		$username = Yii::app()->user->name;
		$model = User::model()->find("username=:username",array(':username'=>$username));
		if (!isset($model->basicInfo)) {
			$model->basicInfo = new BasicInfo;
			$model->basicInfo->userid = $model->id;
			$model->basicInfo->save(false);
		} else {
			$model->basicInfo->saveAttributes($_POST['BasicInfo']);
		}

                
		if(isset($_POST['BasicInfo']['phone']))   // when phone changed set validated to 0
		{
			$model->basicInfo->validated = 0;
			$model->basicInfo->save(false);
		}


		if(isset($_POST['User']))
		{
			$model->saveAttributes($_POST['User']);
		}
		
		$user = User::model()->find("username=:username",array(':username'=>$username));
		$utype = $user->FK_usertype;
			if($utype==1){
			$this->redirect('/JobFair/index.php/profile/view');
			}
			if($utype==2){
				$this->redirect('/JobFair/index.php/profile/viewEmployer');
			}
			else{$this->actionView();}
	}
	
	public function actionEditCompanyInfo()
	{
	
		$username = Yii::app()->user->name;
		$model = User::model()->find("username=:username",array(':username'=>$username));
		
		if(isset($_POST['CompanyInfo']))
		{
			
			$model->companyInfo->saveAttributes($_POST['CompanyInfo']);
		}
	
				$this->redirect('/JobFair/index.php/profile/viewEmployer');

	}
	
	public function actionStudent()
	{
	
		if (isset($_GET['user'])){
			$username = $_GET['user'];
		}
		
		
		$model = User::model()->find("username=:username",array(':username'=>$username));
		if ((User::isCurrentUserStudent() && ($model->username != User::getCurrentUser()->username)) || ($model == null)
				|| (Yii::app()->user->isGuest)) {
			$this->render('profileInvalid');
			return;
		}
		$videoresume = VideoResume::model()->findByPk($model->id);
		$this->render('student', array('user'=>$model,'videoresume'=>$videoresume,));
	}
	
	public function actionEmployer()
	{

		if (isset($_GET['user'])){
			$username = $_GET['user'];
		}
		$model = User::model()->find("username=:username",array(':username'=>$username));

		if ($model->hide_email) {
			$model->email = "<i>hidden</i>";
		}
		
		if ($model->basicInfo->hide_phone){
			$model->basicInfo->phone = "<i>hidden</i>";
		}

		if (!$model->activated || $model->disable){
            if (isset($_GET["activation"]))
            {
                $activation_id = intval($_GET["activation"]);
                User::activeEmployer($activation_id);
                $modle = User::model()->findByPk($activation_id);
                $link= CHtml::link('here', 'http://'.Yii::app()->request->getServerName().'/JobFair/');
                $message = "Your account has just been activated. Click $link to login";
                User::sendEmail($modle->email, "Virtual Job Fair", "Account Activated", $message);
                //User::sendEmployerVerificationEmail($_GET["activation"]);
            }
			$this->render('userInvalid');
		} else {
			$this->render('employer', array('user'=>$model,));
		}
		
	}
	
	//called by ajax
	public function actionGetSkill()
	{
		$skillname = $_GET['name'];
		$skill = Skillset::model()->find("name=:name", array(":name"=>$skillname));
		if (!$skill) {
			print $skillname;
		} else {
			print $skill->id;
		}
		
		return;
	}

    public function actionGetJobInterest()
    {
        $jobinterest = $_GET['job_interest'];
        $interest = User::model()->find("job_interest=:job_interest", array(":job_interest"=>$jobinterest));
        if (!$interest) {
            print $jobinterest;
        } else {
            print $jobinterest;
        }

        return;
    }
	
	
	
	//Specifies access rules
	public function accessRules()
	{
		return array(
				array('allow',  // allow authenticated users to perform these actions
					  'actions'=>array('View', 'ViewEmployer', 'DeleteEducation', 'AddEducation',
					  		'DeleteExperience', 'AddExperience', 'UploadImage', 
					  		'EditStudent', 'UploadResume', 'EditCompanyInfo',
                            'LinkToo','LinkNotification','BlankPage','DuplicationError','UserChoice',
					  		'EditBasicInfo', 'Student', 'Employer','Demo', 'Auth', 'saveSkills', 'getSkill', 'uploadVideo',
                            'getJobInterest', 'saveInterest', 'DeleteInterest'),
					  'users'=>array('@')),
				array('allow',
					  'actions'=>array('videoemployer','videostudent','googleAuth','fiuCsSeniorAuth','fiuAuth',),
					  'users'=>array('*')),
				array('deny', //deny all users anything not specified
					  'users'=>array('*'),
					  'message'=>'Access Denied. Site is unbreakable'),
				);
	}
	

	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'accessControl',			
		);
	}	
	
	
	/*
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
	
	
	public function actionAuth()
	{
		$this->render('auth');
	}
	

	// 		print "<pre>"; print_r($user->id);print "</pre>";return;
	public function actionDemo()
	{
		// if user canceled, redirect to home page
		if (isset($_GET['oauth_problem'])){
			$problem = $_GET['oauth_problem'];
			if ($problem == 'user_refused')
				$this->redirect('/JobFair/index.php');
		}
	
		if (!isset($_SESSION))
			session_start();

        //edit by Manuel making the link dynamic, using Yii. and changing how the account will be link so if the student
        //decide to login with his linkedIn account it will be taken to the account that it is link to.
		$config['base_url']             =   'http://'.Yii::app()->request->getServerName().'/JobFair/index.php/profile/auth.php';
		$config['callback_url']         =   'http://'.Yii::app()->request->getServerName().'/JobFair/index.php/profile/demo';
		$config['linkedin_access']      =   '2rtmn93gu2m4';
		$config['linkedin_secret']      =   'JV0fYG9ls3rclP8v';
	
		include_once Yii::app()->basePath."/views/profile/linkedin.php";
	
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
	   
	  // print "<pre>"; print_r($xml_response);print "</pre>";
	   
	   //print "<pre>"; print_r($data->{'picture-urls'}->{'picture-url'}[0]);print "</pre>";
       // print "<pre>"; print_r($data->{'id'});print "</pre>";
	   
	   //return;

        // check that there is no duplicate id
        $duplicateUser = User::model()->findByAttributes(array('linkedinid'=>$data->{'id'}));
        if ($duplicateUser != null) {
            $this->actionDuplicationError();
            return;
        }

	   // get username and link the accounts
	   $username = Yii::app()->user->name;
	   $user = User::model()->find("username=:username",array(':username'=>$username));
       $user->linkedinid = $data->{'id'};
	   $user->save(false);
	   $user_id = $user->id;

	    
	   // ------------------BASIC INFO---------------
	   $basic_info = null;
	   $basic_info = BasicInfo::model()->findByAttributes(array('userid'=>$user_id));
	   if ($basic_info == null)
	   $basic_info = new BasicInfo(); 
	   $basic_info->userid = $user_id;
	   $basic_info->save(false);
	   // ------------------BASIC INFO -----------------

	   // -----------------EDUCATION ----------------------
	   // get number of educations to add
	   $educ_count = $data->educations['total'];

	   // delete current educations
	   $delete_educs = Education::model()->findAllByAttributes(array('FK_user_id'=>$user_id));
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
	   	$new_educ->FK_user_id = $user_id;
	   	$new_educ->additional_info = $data->educations->education[$i]->notes;
	   	$new_educ->save(false);
	}
	// -----------------EDUCATION ----------------------
	
	// -----------------EXPERIENCE -------------------
	// get number of educations to add
	$pos_count = $data->positions['total'];
	 
	// delete current positions
	$delete_pos = Experience::model()->findAllByAttributes(array('FK_userid'=>$user_id));
	foreach ($delete_pos as $de)
	{
		$de->delete();
	}
	 
	for ($i=0; $i<$pos_count; $i++)
	{
		$new_pos = new Experience();
		$new_pos->FK_userid = $user_id;
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
			//echo 'New Skill ' . $new_skill->attributes;
	   		}
					 
		   	// check if student has that skill, if not add it to student-skill-map table
			if (StudentSkillMap::model()->findByAttributes(array('userid'=>$user_id,
			'skillid'=>Skillset::model()->findByAttributes(array('name'=>$data->skills->skill[$i]->skill->name))->id)) == null){
			$new_sdnt_skill = new StudentSkillMap();
			$new_sdnt_skill->userid = $user_id;
			$new_sdnt_skill->skillid = Skillset::model()->findByAttributes(array('name'=>$data->skills->skill[$i]->skill->name))->id;
			$new_sdnt_skill->ordering = $i + 1;
			$new_sdnt_skill->save(false);
			//echo 'New Skill for student' . $new_sdnt_skill->attributes;
			}
	} 
	// ----------------------end SKILLS----------------------

        //get variables
        $mesg = "LinkedIn";

        $phone = $data->{'phone-numbers'}->{'phone-number'}->{'phone-number'};
        if($phone != null){
            $phone = strip_tags($data->{'phone-numbers'}->{'phone-number'}->{'phone-number'}->asXML());
        }

        $city = $data->location->name;
        if($city != null){
            $city = strip_tags($data->location->name->asXML());
        }

        $state = '';

        $about_me = $data->headline;
        if($about_me != null){
            $about_me = strip_tags($data->headline->asXML());
        }

        $picture = $data->{'picture-urls'}->{'picture-url'}[0];
        if($picture != null){
        $picture = strip_tags($data->{'picture-urls'}->{'picture-url'}[0]->asXML());
        }

        $xemail = $data->{'email-address'};
        if($xemail != null){
            $xemail = strip_tags($data->{'email-address'}->asXML());
        }
        $fname = $data->{'first-name'};
        if($fname != null){
            $fname = strip_tags($data->{'first-name'}->asXML());
        }
        $lname = $data->{'last-name'};
        if($lname != null){
            $lname = strip_tags($data->{'last-name'}->asXML());
        }

        $this->actionLinkToo($xemail,$fname,$lname, $picture ,$mesg,$phone,$city,$state,$about_me);
       // return;

	}

	
	/* 	
	 * GOOGLE LOGIN/REGISTER
	 */
	public function actionGoogleAuth()
	{
		########## Google Settings.. Client ID, Client Secret #############
        //edit by Manuel, making the links dynamic, using Yii
        //To access the google API console to be able to change the setting
        //go to https://code.google.com/apis/console/?noredirect#project:44822970295:access
        //E-mail: virtualjobfairfiu@gmail.com
        //PASS: cis49112014
		$google_client_id       = '44822970295-ub8arp3hk5as3s549jdmgl497rahs6jl.apps.googleusercontent.com';
		$google_client_secret   = 'RsCRTYbGC4VZc40ppLR-4L5h';
		$google_redirect_url    = 'http://'.Yii::app()->request->getServerName().'/JobFair/index.php/profile/googleAuth/oauth2callback';
        $google_developer_key   = 'AIzaSyBRvfT7Djj4LZUrHqLdZfJRWBLubk51ARA';

		//include google api files
		require_once Yii::app()->basePath."/google/Google_Client.php";
		require_once Yii::app()->basePath."/google/contrib/Google_Oauth2Service.php";

		$gClient = new Google_Client();
		$gClient->setApplicationName('Login to JobFair');
		$gClient->setClientId($google_client_id);
		$gClient->setClientSecret($google_client_secret);
		$gClient->setRedirectUri($google_redirect_url);
		$gClient->setDeveloperKey($google_developer_key);

		$google_oauthV2 = new Google_Oauth2Service($gClient);

		//If user wish to log out, we just unset Session variable
		if (isset($_REQUEST['reset']))
		{
			unset($_SESSION['token']);
			$gClient->revokeToken();
			header('Location: ' . filter_var($google_redirect_url, FILTER_SANITIZE_URL));
		}

		if (isset($_GET['code']))
		{
			$gClient->authenticate($_GET['code']);
			$_SESSION['token'] = $gClient->getAccessToken();
			header('Location: ' . filter_var($google_redirect_url, FILTER_SANITIZE_URL));
			return;
		}
		
		// if user canceled, redirect to home page
		if (isset($_GET['error'])){
			$problem = $_GET['error'];
			$this->redirect('/JobFair/index.php');
		}


		if (isset($_SESSION['token']))
		{
			$gClient->setAccessToken($_SESSION['token']);
		}


		if ($gClient->getAccessToken())
		{
			//Get user details if user is logged in
			$user                 = $google_oauthV2->userinfo->get();
			$user_id              = $user['id'];
			$user_name            = filter_var($user['name'], FILTER_SANITIZE_SPECIAL_CHARS);
			$email                = filter_var($user['email'], FILTER_SANITIZE_EMAIL);
			$_SESSION['token']    = $gClient->getAccessToken();
		}
		else
		{
			//get google login url
			$authUrl = $gClient->createAuthUrl();
		}

		if(isset($authUrl)) //user is not logged in, show login button
		{
			$this->redirect($authUrl);
		}

            //link google account to the current one
        $currentUser = User::getCurrentUser();
        if (($currentUser != null) && ($currentUser->FK_usertype == 1))
        {
            // check that there is no duplicate id
            $duplicateUser = User::model()->findByAttributes(array('googleid'=>$user["id"]));
            if ($duplicateUser != null) {
                $this->actionDuplicationError();
                return;
            }

            $username = Yii::app()->user->name;
            $userLink = User::model()->find("username=:username",array(':username'=>$username));
            $userLink->googleid = $user_id;
            $userLink->save(false);

            //get variables
            $mesg = "Google";
            $phone = null;
            $city = null;
            $state = null;
            $about_me = null;
            $this->actionLinkToo($email,$user['given_name'],$user['family_name'],$user['picture'],$mesg,$phone,$city,$state,$about_me);
            return;
        }

        else // user logged in succesfully to google, now check if we register or login to JobFair, link
		{

		
			$userExists = User::model()->findByAttributes(array('googleid'=>$user["id"]));
			// if user exists with googleid, login
			if ($userExists != null) {
				
				if ($userExists->disable != 1)
				{
					$identity = new UserIdentity($userExists->username, '');
					if ($identity->authenticateOutside()) {
						Yii::app()->user->login($identity);
					}
						
					$this->redirect("/JobFair/index.php/home/studenthome");
					return;
				} else {
					$this->redirect("/JobFair/index.php/site/page?view=disableUser");
					return;
				}
			}

			 // register
			 else {
			 	
			 	// check that there is no duplicate user, if so ask if he want to link the accounts
			 	$duplicateUser = User::model()->findByAttributes(array('email'=>$user['email']));
			 	if ($duplicateUser != null) {

                    //populate db
                    $duplicateUser->googleid = $user_id;
                    $duplicateUser->save(false);

                    if ($duplicateUser->disable != 1)
                    {
                        $identity = new UserIdentity($duplicateUser->username, '');
                        if ($identity->authenticateOutside()) {
                            Yii::app()->user->login($identity);
                        }
                        //get variables
                        $mesg = "Google";
                        $phone = null;
                        $city = null;
                        $state = null;
                        $about_me = null;
                        $this->actionLinkToo($email,$user['given_name'],$user['family_name'],$user['picture'],$mesg,$phone,$city,$state,$about_me);
                        return;

                    }
                    else {
                        $this->redirect("/JobFair/index.php/site/page?view=disableUser");
                        return;
                    }

			 	}
			 	$model = new User();
			 	//Populate user attributes
			 	$model->FK_usertype = 1;
			 	$model->registration_date = new CDbExpression('NOW()');
			 	$model->activation_string = 'google';
			 	$model->username = $user["email"];
			 	$model->first_name = $user['given_name'];
			 	$model->last_name = $user['family_name'];
			 	$model->email = $user["email"];
			 	$model->googleid = $user["id"];
                $model->image_url = $user['picture'];
			 	//Hash the password before storing it into the database
			 	$hasher = new PasswordHash(8, false);
			 	$model->password = $hasher->HashPassword('tester');
			 	$model->activated = 1;
			 	$model->save(false);
			 	
			 	// LOGIN
			 	$model = User::model()->find("username=:username",array(':username'=>$model->email));
			 	$identity = new UserIdentity($model->username, 'tester');
			 	if ($identity->authenticate()) {
			 		Yii::app()->user->login($identity);
			 	}
			 	$this->redirect("/JobFair/index.php/user/ChangeFirstPassword");
				
			}

		}

	}
	
	/*
		FIU LOGIN/REGISTER WITH FIU COMPUTER SCIENCE SENIOR CREDENTIALS VIA SENIOR PROYECT WEBSITE API
	*/
	public function actionFiuCsSeniorAuth()
	{
		// include !!!OUR!!! Senior Proyect Website implementation of their API to login
		
		/*
			include FiuCsAuth.php, this file contains the logic to authenticate a user
			using said users' FIU Computer Science Senior Project credentials
		*/
		require_once Yii::app()->basePath."/fiucsauth/FiuCsAuth.php";
		
		// instantiate object
		$fiucsauth = new FiuCsAuth();
		
		// get SPW server status
		$serverStatus = $fiucsauth->getServerStatus();
		
		// check if self POST was made, controller must be aware of this, per Yii logic
		if( isset($panthermail) && isset($pantherid) ){
			// is server up? Guard against SPW indecisiveness...
			if($serverStatus == true){
				// check if we have auth info from SPW
				$userStatus = $fiucsauth->isUserValid($panthermail, $pantherid);
				// user is exists, is authenticated, can login
				if ($userStatus == true){
				// *** Model marker begin ***
					$fiuCsUser = $fiucsauth->getUserInfo();
					//$fiucsauth->debug($fiuCsUser['email'] . "@fiu.edu");


                    $currentUser = User::getCurrentUser();
                    if (($currentUser != null) && ($currentUser->FK_usertype == 1))
                    {

                        // check that there is no duplicate id
                        $duplicateUser = User::model()->findByAttributes(array('fiucsid'=>$fiuCsUser['id']));
                        if ($duplicateUser != null) {
                            $this->actionDuplicationError();
                            return;
                        }

                        $username = Yii::app()->user->name;
                        $userLink = User::model()->find("username=:username",array(':username'=>$username));
                        $userLink->fiucsid  = $fiuCsUser['id'];
                        $userLink->save(false);

                        $mesg = "Senior Project";
                        $picture = null;
                        $phone = null;
                        $city = null;
                        $state = null;
                        $about_me = null;
                        $this->actionLinkToo($fiuCsUser['email'],$fiuCsUser['first_name'],$fiuCsUser['last_name'],$picture,$mesg,$phone,$city,$state,$about_me);
                        return;
                    }

					$userExists = User::model()->findByAttributes(array('fiucsid'=>$fiuCsUser["id"]));
					// if user exists with fiucsseniorid, login
					if ($userExists != null) {
				
						if ($userExists->disable != 1)
						{
							$identity = new UserIdentity($userExists->username, '');
							if ($identity->authenticateOutside()) {
								Yii::app()->user->login($identity);
							}
					
							$this->redirect("/JobFair/index.php/home/studenthome");
							return;
						} else {
							$this->redirect("/JobFair/index.php/site/page?view=disableUser");
							return;
						}
				
				
					}

					// register
					else {
					
						// check that there is no duplicate user
						$duplicateUser = User::model()->findByAttributes(array('email'=>$fiuCsUser['email'] . "@fiu.edu"));
						if ($duplicateUser != null) {

                            //populate db
                            $duplicateUser->fiucsid = $fiuCsUser['id'];
                            $duplicateUser->save(false);

                            if ($duplicateUser->disable != 1)
                            {
                                $identity = new UserIdentity($duplicateUser->username, '');
                                if ($identity->authenticateOutside()) {
                                    Yii::app()->user->login($identity);
                                }

                                //get variables
                                $mesg = "Senior Project";
                                $picture = null;
                                $phone = null;
                                $city = null;
                                $state = null;
                                $about_me = null;

                               // '<window.location.href = >' echo '"'.  $this->createAbsoluteUrl('Profile/LinkNotification/mesg/' . $mesg) . '"';
                                $this->actionLinkToo($fiuCsUser['email'],$fiuCsUser['first_name'],$fiuCsUser['last_name'],$picture,$mesg,$phone,$city,$state,$about_me);
                                return;
                            }
                            else {
                                $this->redirect("/JobFair/index.php/site/page?view=disableUser");
                                return;
                            }
						}
					
						$model = new User();
						//Populate user attributes
						$model->FK_usertype = 1;
						$model->registration_date = new CDbExpression('NOW()');
						$model->activation_string = 'fiucssenior';
						$model->username = $fiuCsUser['email'];
						$model->first_name = $fiuCsUser['first_name'];
						$model->last_name = $fiuCsUser['last_name'];
						$model->email = $fiuCsUser['email']  . "@fiu.edu";
						$model->fiucsid = $fiuCsUser['id'];
						//Hash the password before storing it into the database
						$hasher = new PasswordHash(8, false);
						$model->password = $hasher->HashPassword($fiuCsUser['id']);
						$model->activated = 1;
						$model->save(false);
					
						// LOGIN
						$model = User::model()->find("username=:username",array(':username'=>$model->email));
						// constructor for this class takes as parameters username and password
						$identity = new UserIdentity($fiuCsUser['email'], $fiuCsUser['id']);
						if ($identity->authenticate()) {
							Yii::app()->user->login($identity);
						}
						$this->redirect("/JobFair/index.php/user/ChangeFirstPassword");

					}				
				//*** model marker end ***
				}
				//$this->redirect('http://www.reddit.com');
			}
			else{
				//$this->redirect('http://www.tabasco.com');
			}
		}
	}
	
	/*
	 * FIU LOGIN/REGISTER
	*/
	public function actionFiuAuth()
	{
		########## Google Settings.. Client ID, Client Secret #############
         //edit by Manuel, making the links dynamic, using Yii
         //To access the google API console to be able to change the setting
         //go to https://code.google.com/apis/console/?noredirect#project:44822970295:access
         //E-mail: virtualjobfairfiu@gmail.com
         //PASS: cis49112014
		$google_client_id   = '44822970295-ub8arp3hk5as3s549jdmgl497rahs6jl.apps.googleusercontent.com';
		$google_client_secret   = 'RsCRTYbGC4VZc40ppLR-4L5h';
		$google_redirect_url    = 'http://'.Yii::app()->request->getServerName().'/JobFair/index.php/profile/fiuAuth/oauth2callback';
		$google_developer_key   = 'AIzaSyBRvfT7Djj4LZUrHqLdZfJRWBLubk51ARA';

		//include google api files
		require_once Yii::app()->basePath."/fiu/Google_Client.php";
		require_once Yii::app()->basePath."/fiu/contrib/Google_Oauth2Service.php";

		$gClient = new Google_Client();
		$gClient->setApplicationName('Login to JobFair');
		$gClient->setClientId($google_client_id);
		$gClient->setClientSecret($google_client_secret);
		$gClient->setRedirectUri($google_redirect_url);
		$gClient->setDeveloperKey($google_developer_key);

		$google_oauthV2 = new Google_Oauth2Service($gClient);

		//If user wish to log out, we just unset Session variable
		if (isset($_REQUEST['reset']))
		{
			unset($_SESSION['token']);
			$gClient->revokeToken();
			header('Location: ' . filter_var($google_redirect_url, FILTER_SANITIZE_URL));
		}

		if (isset($_GET['code']))
		{
			$gClient->authenticate($_GET['code']);
			$_SESSION['token'] = $gClient->getAccessToken();
			header('Location: ' . filter_var($google_redirect_url, FILTER_SANITIZE_URL));
			return;
		}

		// if user canceled, redirect to home page
		if (isset($_GET['error'])){
			$problem = $_GET['error'];
			$this->redirect('/JobFair/index.php');
		}


		if (isset($_SESSION['token']))
		{
			$gClient->setAccessToken($_SESSION['token']);
		}


		if ($gClient->getAccessToken())
		{
			//Get user details if user is logged in
			$user                 = $google_oauthV2->userinfo->get();
			$user_id              = $user['id'];
			$user_name            = filter_var($user['name'], FILTER_SANITIZE_SPECIAL_CHARS);
			$email                = filter_var($user['email'], FILTER_SANITIZE_EMAIL);
			$_SESSION['token']    = $gClient->getAccessToken();
		}
		else
		{
			//get google login url
			$authUrl = $gClient->createAuthUrl();
		}

		if(isset($authUrl)) //user is not logged in, show login button
		{
			$this->redirect($authUrl);
		}

        //link Fiu Account account to the current one
        $currentUser = User::getCurrentUser();
        if (($currentUser != null) && ($currentUser->FK_usertype == 1))
        {
            // check that there is no duplicate id
            $duplicateUser = User::model()->findByAttributes(array('fiu_account_id'=>$user_id));
            if ($duplicateUser != null) {
                $this->actionDuplicationError();
                return;
            }

            $username = Yii::app()->user->name;
            $userLink = User::model()->find("username=:username",array(':username'=>$username));
            $userLink->fiu_account_id = $user_id;
            $userLink->save(false);

            //get variables
            $mesg = "FIU Email";
            $phone = null;
            $city = null;
            $state = null;
            $about_me = null;
            $this->actionLinkToo($email,$user['given_name'],$user['family_name'],$user['picture'],$mesg,$phone,$city,$state,$about_me);
            return;
        }
		else // user logged in succesfully to google, now check if we register or login to JobFair
		{


			$userExists = User::model()->findByAttributes(array('fiu_account_id'=>$user["id"]));
			// if user exists with fiu_account_id, login
			if ($userExists != null) {
				
				if ($userExists->disable != 1)
				{
					$identity = new UserIdentity($userExists->username, '');
					if ($identity->authenticateOutside()) {
						Yii::app()->user->login($identity);
					}
					
					$this->redirect("/JobFair/index.php/home/studenthome");
					return;
				} else {
					$this->redirect("/JobFair/index.php/site/page?view=disableUser");
					return;
				}
				
				
			}

			// register
			else {
					
				// check that there is no duplicate user
				$duplicateUser = User::model()->findByAttributes(array('email'=>$user['email']));
				if ($duplicateUser != null) {

                    //populate db
                    $duplicateUser->fiu_account_id = $user_id;
                    $duplicateUser->save(false);

                    if ($duplicateUser->disable != 1)
                    {
                        $identity = new UserIdentity($duplicateUser->username, '');
                        if ($identity->authenticateOutside()) {
                            Yii::app()->user->login($identity);
                        }
                        //get variables
                        $mesg = "FIU Email";
                        $phone = null;
                        $city = null;
                        $state = null;
                        $about_me = null;
                        $this->actionLinkToo($email,$user['given_name'],$user['family_name'],$user['picture'],$mesg,$phone,$city,$state,$about_me);
                        return;
                    }
                    else {
                        $this->redirect("/JobFair/index.php/site/page?view=disableUser");
                        return;
                    }
				}
					
				$model = new User();
				//Populate user attributes
				$model->FK_usertype = 1;
				$model->registration_date = new CDbExpression('NOW()');
				$model->activation_string = 'fiu';
				$model->username = $user["email"];
				$model->first_name = $user['given_name'];
				$model->last_name = $user['family_name'];
				$model->email = $user["email"];
				$model->fiu_account_id = $user["id"];
                $model->image_url = $user['picture'];
				//Hash the password before storing it into the database
				$hasher = new PasswordHash(8, false);
				$model->password = $hasher->HashPassword('tester');
				$model->activated = 1;
				$model->save(false);
					
				// LOGIN
				$model2 = User::model()->find("username=:username",array(':username'=>$model->email));
				$identity = new UserIdentity($model2->username, 'tester');
				if ($identity->authenticate()) {
					Yii::app()->user->login($identity);
				}
				$this->redirect("/JobFair/index.php/user/ChangeFirstPassword");

			}
		}

	}
    public function actionLinkToo($email, $first_name, $last_name, $picture,$mesg,$phone,$city,$state,$about_me)
    {
        $model = new LinkTooForm();
        $this->render('LinkToo', array('model'=>$model, 'email'=>$email, 'first_name'=>$first_name,'last_name'=>$last_name,
            'picture'=>$picture, 'mesg'=>$mesg , 'phone'=>$phone, 'city'=>$city, 'state'=>$state, 'about_me'=>$about_me));

        return;
    }
    public function actionUserChoice()
    {

        if(isset($_POST['LinkTooForm']))
        {
            $user = User::getCurrentUser();
            $basic_info = BasicInfo::model()->findByAttributes(array('userid'=>$user->id));

            $model = new LinkTooForm();
            $model->attributes = $_POST['LinkTooForm'];
            $mesg = $model->toPost;

            if($model->profilePic != null){
                $user->image_url = $model->profilePic;
                $user->save(false);
            }
            if($model->profilePicVar != null){
                $user->image_url = $model->profilePicVar;
                $user->save(false);
            }
            if($model->firstname != null){
                $user->first_name = $model->firstname;
                $user->save(false);
            }
            if($model->firstnamevar != null){
                $user->first_name = $model->firstnamevar;
                $user->save(false);
            }
            if($model->lastname != null){
                $user->last_name = $model->lastname;
                $user->save(false);
            }
            if($model->lastnamevar != null){
                $user->last_name = $model->lastnamevar;
                $user->save(false);
            }
            if($model->email != null){
                $user->email = $model->email;
                $user->save(false);
            }
            if($model->emailvar != null){
                $user->email = $model->emailvar;
                $user->save(false);
            }
            if($model->phone != null){
                $basic_info->phone = $model->phone;
                $basic_info->save(false);
            }
            if($model->phonevar != null){
                $basic_info->phone = $model->phonevar;
                $basic_info->save(false);
            }
            if($model->city != null){
                $basic_info->city = $model->city;
                $basic_info->save(false);
            }
            if($model->cityvar != null){
                $basic_info->city = $model->cityvar;
                $basic_info->save(false);
            }
            if($model->state != null){
                $basic_info->state = $model->state;
                $basic_info->save(false);
            }
            if($model->statevar != null){
                $basic_info->state = $model->statevar;
                $basic_info->save(false);
            }
            if($model->about_me != null){
                $basic_info->about_me = $model->about_me;
                $basic_info->save(false);
            }
            if($model->about_me_var != null){
                $basic_info->about_me = $model->about_me_var;
                $basic_info->save(false);
            }

        }


        Yii::app()->end();
    }


    public function actionLinkNotification($mesg)
    {
        $model = new LinkTooForm();
        $this->render('LinkNotification', array('model'=>$model, 'mesg'=>$mesg));
    }
    public function actionBlankPage()
    {
        $this->render('BlankPage');
        return;
    }
    public function actionDuplicationError()
    {
        $model = new LinkTooForm();
        $this->render('duplicationError', array('model'=>$model));
    }



}
