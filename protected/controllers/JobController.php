<?php

class JobController extends Controller
{
	private function isExpired($job){
		if (strtotime($job->deadline) < (strtotime("-1 day",strtotime("now")))){
			return true;
		} else {
			return false;
		}
	}

	public function actionView($jobid)
	{
		$job = Job::model()->findByPk($jobid);		
		
		//foreach ($skills->skillset as $skillset) {
			
		//}
		if ($this->isExpired($job)){
			$job->active = 0;
			$job->save();
		}
		
		if ($job == null) {
			$this->render('JobInvalid');
		} else {
			$this->render('View', array('job' => $job));
		}
		
	}
	
	public function actionHome($type = null, $companyname = null){

		if (isset($type) && $type != ""){
			$jobs = Job::model()->findAllBySql("SELECT * FROM job WHERE active='1' AND type=:type ORDER BY deadline DESC", array(":type"=>$type));
		} else {
			$jobs = Job::model()->findAllBySql("SELECT * FROM job WHERE active='1' ORDER BY deadline DESC");
		}
		if (isset($companyname) && $companyname != ""){
			$companyjobs = array();
			foreach($jobs as $job){
				$name = $job->fKPoster->companyInfo['name'];
				if ($name == $companyname) {
					$companyjobs[] = $job;
				}
			}
			$jobs = $companyjobs;
		}
		
		$this->render('home', array('jobs'=>$jobs));
	}
	
	public function mynl2br($text) {
		return strtr($text, array("\r\n" => '<br />', "\r" => '<br />', "\n" => '<br />'));
	}
	
	public function actionPost()
	{
	    $model = new Job;
		
	    if(isset($_POST['Job']))
	    {
	    	if (!($this->actionVerifyJobPost() == "")) {
	    		$this->render('post',array('model'=>$model));
	    	}
	        $model->attributes=$_POST['Job'];
        	$model->FK_poster = User::getCurrentUser()->id;
            date_default_timezone_set('America/New_York');
            $model->post_date = date('Y-m-d H:i:s');
        	$model->description = $this->mynl2br($_POST['Job']['description']);
            $model->save(false);
            if (isset($_POST['Skill'])) {
            	$this->actionSaveSkills($model->id);
            }
            
            
            $link = 'http://'.Yii::app()->request->getServerName().'/JobFair/index.php/job/view/jobid/'.$model->id;
            //$link = 'http://localhost/JobFair/JobFair/index.php/job/view/jobid/'.$model->id;
            $message = User::getCurrentUser()->username." just posted a new job: ".$model->title. ". Click here to view the post. ";
            User::sendAllStudentVerificationAlart($model->FK_poster, $model->fKPoster->username, $model->fKPoster->email, $message, $link);
            $this->redirect("/JobFair/index.php/Job/studentmatch/jobid/" . $model->id);
	        
	    }
	    
	    $this->render('post',array('model'=>$model));
	}
	
	public function actionEditJobPost()
	{
		if(isset($_POST['Job']))
		{
			$jobid = $_POST['Job'];
			//print "<pre>"; print_r($jobid);print "</pre>";return;
			//$jobid = $_POST['id'];
			$model = Job::model()->findByPk($jobid);
			//print "<pre>"; print_r($userid);print "</pre>";return;
			//$model = Job::model()->find("FK_poster=:FK_poster",array(':FK_poster'=>$jobid));
			$model->attributes=$_POST['Job'];
			$model->save(false);
			if (isset($_POST['Skill'])) {
				$this->actionSaveSkills($model->id);
			}

			$this->redirect("/JobFair/");
			 
		}
		 
		$this->render('post',array('model'=>$model));

	}
	
	
	
	
	
	public function actionSaveSkills($jobid){
		$skills = $_POST['Skill'];
		//first wipe out the jobs skills
		
		$job = Job::model()->findByPk($jobid);
		if ($job){
			foreach($job->jobSkillMaps as $skill){
				$skill->delete();
			}
		}
		
		$i = 1;
		foreach($skills as $skill){
			$skillmap = new JobSkillMap;
			$skillmap->jobid = $jobid;
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
	}
	
	public function actionQuerySkill($name){
		$skillname = $_GET['name'];
		$skill = Skillset::model()->find("name=:name", array(":name"=>$skillname));
		if (!$skill) {
			print "No Skill";
		} else {
			print $skill->name . ',' . $skill->id;
		}
		
		return;
	}
	public function actionApply($jobid){
		$user = User::getCurrentUser();
		$job = Job::model()->findByPk($jobid);
		$poster = User::model()->findByPk($job->FK_poster);
		$application = new Application;
		$application->jobid = $job->id;
		$application->userid = $user->id;
		$application->application_date = date('Y-m-d H:i:s');
		$application->coverletter = $this->mynl2br($_POST['Application']['coverletter']);
		$application->save(false);
		$link = 'http://'.Yii::app()->request->getServerName().'/JobFair/index.php/profile/student/user/'.$user->username;
		$link1= CHtml::link('click here to see '.$user->username.' profile', 'http://'.Yii::app()->request->getServerName().'/JobFair/index.php/profile/student/user/'.$user->username);
		$message = "The User ".$user->username. " just applied for your job ".$job->title.". Click here to view his profile";		
		$message1 = "$user->username just applied for your job $job->title<br/>$link1";
		$html = User::replaceMessage($poster->username, $message1);
		User::sendEmployerNotificationAlart($user->id, $job->FK_poster, $message, $link, 3);
		User::sendEmailNotificationAlart($poster->email, $poster->username, $user->username ,$html);
		$this->redirect("/JobFair/index.php/Job/View/jobid/" . $jobid);
		
	}
	
	public function actionClose($jobid){
		$user = User::getCurrentUser();
		
		$job = Job::model()->findByPk($jobid);
		if ($user->id == $job->FK_poster) {
			$job->active = 0;
			$job->save(false);
		}
	
		$this->redirect("/JobFair/index.php/Job/View/jobid/" . $jobid);
	
	}
	
	function queryForSkill($skillid, $skillmap){
		foreach ($skillmap as $skill){
			if ($skill->skillid == $skillid){
				return $skill;
			}
		}
		return null;
	}
	
	function compare_skills($jobskillmaps, $studentskillmaps){
		//first take out all irrelevant skills from the student
		foreach($studentskillmaps as $skill){
			$studentskills[] = $skill->skillid;
		}
		
		foreach($jobskillmaps as $skill){
			$jobskills[] = $skill->skillid;
		}

		if (!isset($studentskills) || !isset($jobskills)){
			return 0;
		} else {
			$studentskills = array_intersect($studentskills, $jobskills);
			$score =  (count($studentskills) / count($jobskills));
			$skilldifference = 1;
			foreach($studentskills as $skillid){
				$studentSkillObject = $this->queryForSkill($skillid, $studentskillmaps);
				$jobSkillObject =  $this->queryForSkill($skillid, $jobskillmaps);
				$skilldifference += ($studentSkillObject->ordering - $jobSkillObject->ordering);
			}
			if ($skilldifference == 0) {
				$skilldifference ++;
			}
			$score -=  $skilldifference / 100;
			return $score;
		}

	}
	
	public function actionViewApplication($jobid, $userid) {
	
			$applicationModel = Application::model()->find("jobid=:jobid AND userid=:userid", array("jobid"=> $jobid, "userid" =>$userid));
			$job = Job::model()->findByPk($applicationModel->jobid);
			$this->render('viewApplication', array('application'=>$applicationModel, 'job' => $job));
			
	}
	public function actionStudentMatch($jobid) {
		

		$students = User::model()->findAll("FK_usertype = 1 AND (disable IS NULL OR disable = 0) AND activated = 1");
		$job = Job::model()->findByPk($jobid);
		if ($job == null) {
			$this->render('JobInvalid');
			return;
		}
		if ($job->FK_poster != User::getCurrentUser()->id) {
			$this->render('studentmatcherror', array('students'=>$students));
			return;
		}

		if (!isset($job->jobSkillMaps) || (sizeof($job->jobSkillMaps) == 0)){
			$this->render('studentmatch', array('students'=>null));
			return;
		}
		
		foreach ($students as $student){
			$student->skillrating = $this->compare_skills($job->jobSkillMaps, $student->studentSkillMaps);
			
		}
		//return;
		function cmp($student1,$student2) {
			if ($student1->skillrating == $student2->skillrating)
				return 0;
			return ($student1->skillrating < $student2->skillrating) ? 1 : -1;
		}
		
		usort($students, 'cmp');
		$size = 3;

		foreach($students as $key => $student){
			if ($student->skillrating <= 0){
				unset($students[$key]);
			}
		}
		while (isset($students[$size + 1])){
			if ($students[$size]->skillrating == $students[$size + 1]->skillrating) {
				$size ++;
			} else {
				break;
			}
		}
		
		$students = array_slice($students, 0, $size + 1);
		if ($job->matches_found != 1) {
			$job->matches_found = 1;
			foreach($students as $student) {
				//SENDNOTIFICATION to each student, a job has been posted that matches your skills
			$joblink = CHtml::link(CHtml::encode('View Job'), "/JobFair/index.php/job/view/jobid/" . $job->id , array('target'=>'_blank', 'style' =>'float:left'));
			$link = 'http://'.Yii::app()->request->getServerName().'/JobFair/index.php/job/view/jobid/'.$job->id;
			$sender = User::model()->findByPk($job->FK_poster);
			$message = "Hi ".$student->username.", the company ".$sender->username." just posted a job ".$job->title." that matches your skills";
			User::sendStudentNotificationMatchJobAlart($sender->id, $student->id, $link, $message);	
			//SEND EMAIL NOTIFICATION
			}
		}
		//return;

		$this->render('studentmatch', array('students'=>$students));
	}
	
	
	
	
	public function actionVerifyJobPost(){
		$job = $_POST['Job'];
		$error = "";
	
		$type = $job['type'];
		$title = $job['title'];
		$compensation = $job['compensation'];
		$description = $job['description'];
		$deadline = $job['deadline'];
	
		if (strlen($type) < 1) {
			$error .= "You must select a job type<br />";
		}
		
		if (strlen($title) < 1) {
			$error .= "You must input a job title<br />";
		}
		
		if (strlen($description) < 1) {
			$error .= "You must input a job description<br />";
		}
		
		if (strlen($deadline) < 1) {
			$error .= "You must select a job type<br />";
		}
		
// 		if (strlen($compensation) < 1) {
// 			$error .= "You must input an amount for compensation<br />";
// 		}
	
		if (!$this->is_valid_date($deadline)) {
			$error .= "Please enter date in the format: yyyy-mm-dd<br />";
		}
		print $error;
		return $error;
	}
	
	public function actionVirtualHandshake($jobid, $studentid) {
		$handshake = new Handshake;
		$handshake->jobid = $jobid;
		$handshake->studentid = $studentid;
		$handshake->employerid = User::getCurrentUser()->id;
		$student = User::model()->findByPk($studentid);
		
		if ((Job::hasHandShake($jobid, User::getCurrentUser()->id, $studentid) == "") &&
			User::getCurrentUser()->isAEmployer() && $student->isAStudent()){
			$handshake->save(false);
			//SENDNOTIFICATION to $student, an employer is interested you, apply for this job
			$joblink = CHtml::link(CHtml::encode('View Job'), "/JobFair/index.php/job/view/jobid/" . $jobid , array('target'=>'_blank', 'style' =>'float:left'));
			$link = 'http://'.Yii::app()->request->getServerName().'/JobFair/index.php/job/view/jobid/'.$jobid;
			$job = Job::model()->findByPk($jobid);
			$message = User::getCurrentUser()->username." is interested in you for the following job post: ".$job->title." Click here to view the post and consider applying.";
			User::sendUserNotificationHandshakeAlart($handshake->employerid, $studentid, $link, $message);
			//SENT EMAIL NOTIFICATION	
			$link1= CHtml::link('click here to see '.$job->title.' page', 'http://'.Yii::app()->request->getServerName().'/JobFair/index.php/job/view/jobid/' . $jobid);
			$message1 =  User::getCurrentUser()->username." is interested in you for the following job post:  " .$job->title."<br/>$link1";
			$html = User::replaceMessage($student->username, $message1);
			User::sendEmailStudentNotificationVirtualHandshakeAlart($student->email, $student->username, User::getCurrentUser()->username ,$html);
		}

		return;
	}
	
	function is_valid_date($value){
		return preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $value);
	}
	
	//Specifies access rules
	public function accessRules()
	{
		
		return array(
				array('allow',  // allow authenticated users to perform these actions
					  'actions'=>array('StudentMatch', 'View', 'Home', 'Post', 
					  		'SaveSkills', 'studentMatch','EditJobPost','VerifyJobPost', 'View' ,'VirtualHandshake', 'QuerySkill', 'Apply',
					  		'viewApplication', 'Close', 'Search'),
					  'users'=>array('@')),
				array('allow',
					  'actions'=>array('Home'),
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

    // job search from nav bar
    public function actionSearch()
    {
        $flag = 1;
        $keyword = ($_POST['keyword']); // Get words to search
        $pieces = trim($keyword);
        $pieces = explode(" ", $pieces); // split words to search by space
        $count = sizeof($pieces);          // get number of word to search
        $query = '';
        for($i = 0; $i < $count;$i++) // prepare query
        {
            if ($i == $count - 1){
                $query = $query.'name like \'%'.$pieces[$i].'%\'';
            } else {
                $query = $query.'name like \'%'.$pieces[$i].'%\' OR ';
            }
        }

        $criteria = new CDbCriteria; // query criteria
        $criteria->condition = $query;
        $results = Array();

        // there are words to search
        if ($keyword != null){

            // *********  Search by job type  i.e. Full Time, Part Time **************
            $jobKeyword = Job::model()->findAllBySql("SELECT * FROM job WHERE active='1' AND job.type=:jobtype ORDER BY deadline DESC", array(":jobtype"=>$keyword));
            // there exists type in Job
            foreach($jobKeyword as $jk)
            {
                if($jk != null)
                {
                    $results[] = $jk;   // add job to results array
                }
            }

            // *********** Search by job title  **********
           $jobTitle = Job::model()->findAllBySql("SELECT * FROM job WHERE active='1' AND title=:title ORDER BY deadline DESC", array(":title"=>$keyword));
            // there exists job title in Job
            foreach($jobTitle as $jk)
            {
                if($jk != null)
                {
                    $results[] = $jk; // add job to results array
                }
            }


            // ******** Search by Company name ***********
            $compName = CompanyInfo::model()->findAllBySql("SELECT FK_userid FROM company_info WHERE company_info.name=:coName", array(":coName"=>$keyword));
            $compID = Job::model()->findAllBySql("SELECT * FROM job WHERE active='1' AND FK_poster=:FK_poster ORDER BY deadline DESC", array(":FK_poster"=>$compName) );
            // there exists company keyword
            foreach($compID as $jk)
            {
                if($jk != null)
                {
                    $results[] = $jk; // add job to results array
                }
            }

            // ************   Search by skills  **********
            $skillsArray = Skillset::model()->findAll($criteria);  // array containing skills from Skillset table
            foreach ($skillsArray as $sk)
            {
                if ($sk != null){
                    $jobIds = JobSkillMap::model()->findAllByAttributes(array('skillid'=>$sk->id)); // get all jobs id with that skill id
                    if ($jobIds != null) {      // if there exits jobs with skill id
                        foreach ($jobIds as $ji)
                        {
                            if ($ji != null){
                                $jobid = $ji->jobid;
                                $duplicate = 0;
                                if (sizeof($results) > 0){
                                    foreach($results as $t){  // search for duplicates
                                        if ($t != null){
                                            if (strcmp($t->id,$jobid) == 0){
                                                $duplicate = 1;
                                            }
                                        }
                                    }
                                }

                                if ($duplicate == 0){
                                    $results[] = Job::model()->findByAttributes(array('id'=>$jobid, 'active'=>'1'));	// get job matching job id & job post have to be active
                                }
                            }
                        }

                    }
                }
            }


            // get company information
            $compsArray = CompanyInfo::model()->findAll($criteria);
            foreach ($compsArray as $co){
                if ($co != null){
                    $comp_posts = Job::model()->findAllByAttributes(array('FK_poster'=>$co->FK_userid));
                    if ($comp_posts != null){
                        foreach ($comp_posts as $cp){
                            $duplicate = 0;
                            if (sizeof($results) > 1){
                                if ($cp != null){
                                    foreach($results as $t){
                                        if ($t != null){
                                            if ($t->id == $cp->id){
                                                $duplicate = 1;
                                            }
                                        }
                                    }
                                }
                            }

                            if ($duplicate == 0){
                                $results[] = Job::model()->findByAttributes(array('id'=>$cp->id, 'active'=>'1'));
                            }
                        }
                    }
                }
            }
        }


        if (isset($_GET['user'])){
            $username = $_GET['user'];
        } else {
            $username = Yii::app()->user->name;
        }
        $user = User::model()->find("username=:username",array(':username'=>$username)); // pass user
        $skills = Skillset::getNames(); // pass skills
        $companies = CompanyInfo::getNames(); // pass companies
        // render results to job/home
        $this->render('home',array('results'=>$results,'user'=>$user,'companies'=>$companies,'skills'=>$skills,'flag'=>$flag,));

    }


}