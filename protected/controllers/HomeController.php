<?php

class HomeController extends Controller
{
	public function actionStudentHome()
	{
		$username = Yii::app()->user->name;
		$user = User::model()->find("username=:username",array(':username'=>$username)); // pass the user
		$notification = Notification::model()->getNotificationId($user->id); // pass the notifications
		$companies = CompanyInfo::getNames(); // pass the companies
		$skills = Skillset::getNames(); // pass the skills
		
//  		$dbCommand = Yii::app()->db->createCommand("SELECT skillid,COUNT(*) as count1 FROM `job_skill_map` GROUP BY `skillid` ORDER BY 'count1'");
//  		$skillids = $dbCommand->queryAll();
		
		$criteria= new CDbCriteria();
		$criteria=array(
				'group'=>'skillid',
				'select'=>'skillid,count(*) as cc',
				'order'=>'cc desc'
		);
		
		$skillids = JobSkillMap::model()->findAll($criteria);
				
		$most_wanted_skills =  Array();
		$i = 0;
		
		foreach ($skillids as $sk){
			$most_wanted_skills[] = Skillset::model()->findByAttributes(array('id'=>$sk->skillid));
			$i++;
			if ($i == 5){
				break;
			}
		}		
		
		$countvideo = 0;
		$countmachingjobs = 0;
		$countmessages = 0;
		$countmisc =0;
		foreach ($notification as $n) {
					if ($n->importancy == 4 & $n->been_read == 0 ) {
			$countvideo++;		
			$key = VideoInterview::model()->findByAttributes(array('notification_id' => ($n->id + 1)));
			if($key != null){
			$n->keyid = $key->session_key;
			
			}
			//print "<pre>"; print_r($key);print "</pre>";return;
			}
			else if ($n->importancy == 4 & $n->been_read != 0 ) {
				//$countvideo++;
				$key = VideoInterview::model()->findByAttributes(array('notification_id' => ($n->id + 1)));
				if($key != null){
					$n->keyid = $key->session_key;
					//print "<pre>"; print_r($key);print "</pre>";return;
				}
			}
			elseif($n->importancy == 2 & $n->been_read == 0)
			$countmachingjobs++;
			elseif ($n->importancy == 3 & $n->been_read == 0)	
			$countmessages++;
			elseif ($n->importancy == 1 & $n->been_read == 0)
			$countmisc++;
		}		
		
		
		$this->render('studenthome', array('user'=>$user,'companies'=>$companies,'skills'=>$skills, 'notification'=>$notification, 'mostwanted'=>$most_wanted_skills, 'countvideo'=>$countvideo, 'countmachingjobs'=>$countmachingjobs, 'countmessages'=>$countmessages, 'countmisc'=>$countmisc));

	}
	
	public function actionNew(){
		$this->render('vid');
	}
	
	public function actionHello(){
		$this->render('newfile1');
	}
	

	public function actionEmployerHome()
	{
		$username = Yii::app()->user->name;
		$user = User::model()->find("username=:username",array(':username'=>$username)); // pass user
		$notification = Notification::model()->getNotificationId($user->id); // pass the notifications
		$univs = School::getAllSchools(); // pass universities
		$skills = Skillset::getNames(); // pass skills
		
		
		
		
		$countvideo = 0;
		$countapplicants = 0;
		$countmessages = 0;
		$countcandidates =0;
		foreach ($notification as $n) {
			if ($n->importancy == 4 & $n->been_read == 0 ) {
			$countvideo++;		
			$key = VideoInterview::model()->findByAttributes(array('notification_id' => $n->id));
			if($key != null){
			$n->keyid = $key->session_key;
			//print "<pre>"; print_r($key);print "</pre>";return;
			}
			}
			else if ($n->importancy == 4 & $n->been_read != 0 ) {
				//$countvideo++;
				$key = VideoInterview::model()->findByAttributes(array('notification_id' => $n->id));
				if($key != null){
					$n->keyid = $key->session_key;
					//print "<pre>"; print_r($key);print "</pre>";return;
				}
			}
			elseif($n->importancy == 6 & $n->been_read == 0)
			$countapplicants++;			
			elseif ($n->importancy == 3 & $n->been_read == 0)	
			$countmessages++;
			elseif ($n->importancy == 5 & $n->been_read == 0)
			$countcandidates++;
		}		

		$this->render('employerhome', array('user'=>$user,'universities'=>$univs,'skills'=>$skills, 'notification'=>$notification, 'countvideo'=>$countvideo, 'countapplicants'=>$countapplicants, 'countmessages'=>$countmessages, 'countcndidates'=>$countcandidates ));
	}
	
	public function actionAdminHome()
	{
//		$results = null;
//		$results1 = null;
//
//		$username = Yii::app()->user->name;
//		$user = User::model()->find("username=:username",array(':username'=>$username));
//
//		$notification = Notification::model()->getNotificationId($user->id); // pass the notifications
//        $matchnotification = MatchNotification::model()->findBySql("SELECT * FROM match_notification ORDER BY date_modified DESC limit 1");
//        $status = Array('status'=>$matchnotification['status'], 'date_modified'=>$matchnotification['date_modified'], 'userid'=>$matchnotification['userid']);
//        $user = User::model()->find("id=:id",array(':id'=>$matchnotification['userid']));
//        $status['username'] = $user['username'];
        $this->redirect($this->createUrl('UserCrud/admin'));
		//$this->render('admin', array('results'=>$results, 'results1'=>$results1, 'notification'=>$notification, 'matchnotification'=>$status));
	}

	
	public function accessRules()
	{
		return array(
				array('allow',  // allow authenticated users to perform these actions
						'actions'=>array('NotificationAdmin', 'StudentHome', 'MergeSkills', 'AddSkill', 'EmployerHome', 'Search', 'Search2','Employersearch', 'New', 'Hello', 'AdminHome', 'adminsearch', 'DisableUser', 'EnableUser', 'DeleteJob', 'DeleteNotification', 'AcceptNotificationSchedualInterview', 'CareerPathSync'),
						'users'=>array('@')),
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

	public function actionEmployersearch()
	{
		$srch_keyword = ($_POST['skillkeyword']); // Get skill keyword to search
		$pieces = trim($srch_keyword);
		$pieces = explode(" ", $pieces); // split words to search
		$count = sizeof($pieces); // get number of word to search
		$query = '';
		for($i = 0; $i < $count;$i++) // prepare query
		{
			if ($i == $count - 1){
				$query = $query.'name like \'%'.$pieces[$i].'%\'';
			} else {
				$query = $query.'name like \'%'.$pieces[$i].'%\' OR ';
			}

		}
		
		$criteria = new CDbCriteria;
		$criteria->condition = $query;
		$results = Array();

		if ($srch_keyword != null){

			$skillsArray = Skillset::model()->findAll($criteria);
			foreach ($skillsArray as $sk)
			{
				$student_ids = StudentSkillMap::model()->findAllByAttributes(array('skillid'=>$sk->id)); // search student skill map for students with that skill
				foreach ($student_ids as $tmp){
					$duplicate = 0;
					if (sizeof($results) > 0){
						foreach($results as $t){
							if ($t->id == $tmp->userid){
								$duplicate = 1;
							}
						}
					}

					if ($duplicate == 0){
						$results[] = User::model()->findByAttributes(array('id'=>$tmp->userid));
					}
				}
			}

			$school_id = School::model()->findAll($criteria); // get school ID
			foreach ($school_id as $si){
				$student_ids = Education::model()->findAllByAttributes(array('FK_school_id'=>$si->id)); // search educations with school ID
				foreach ($student_ids as $tmp){
					$duplicate = 0;
					if (sizeof($results) > 0){
						foreach($results as $t){
							if ($t->id == $tmp->FK_user_id){
								$duplicate = 1;
							}
						}
					}

					if ($duplicate == 0){
						$results[] = User::model()->findByAttributes(array('id'=>$tmp->FK_user_id));
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
		$universites = School::getAllSchools(); // pass companies

		// 		foreach ($results as $tr){
		// 			print "<pre>"; print_r($tr->attributes);print "</pre>";
		// 		}
		// 		return;
		
		$this->render('employerSearchResults', array('results'=>$results, 'skills'=>$skills, 'universities'=>$universites, 'user'=>$user));

	}

	public function actionSearch2()
	{
		$keyword = ($_GET['key']); // Get words to search
		$pieces = trim($keyword);
		$pieces = explode(" ", $pieces); // split words to search
		$count = sizeof($pieces); // get number of word to search
		$query = '';
		for($i = 0; $i < $count;$i++) // prepare query
		{
			
			if ($i == $count - 1){
				$query = $query.'name =\''.$pieces[$i].'\'';
			} else {
				$query = $query.'name =\''.$pieces[$i].'\' OR ';
			}	
		}
	
		$criteria = new CDbCriteria;
		$criteria->condition = $query;
		$results = Array();
	
		if ($keyword != null){
			$skillsArray = Skillset::model()->findAll($criteria);
			foreach ($skillsArray as $sk)
			{
				if ($sk != null){
					$jobIds = JobSkillMap::model()->findAllByAttributes(array('skillid'=>$sk->id)); // get all jobs with that skill
					if ($jobIds != null) {
						foreach ($jobIds as $ji)
						{
							if ($ji != null){
								$jobid = $ji->jobid;
								$duplicate = 0;
								if (sizeof($results) > 0){
									foreach($results as $t){
										if ($t != null){
											if (strcmp($t->id,$jobid) == 0){
												$duplicate = 1;
											}
										}
									}
								}
									
								if ($duplicate == 0){
									$results[] = Job::model()->findByAttributes(array('id'=>$jobid, 'active'=>'1'));	// search for skill only
								}
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
	
		$this->render('studentSearchResults',array('results'=>$results,'user'=>$user,'companies'=>$companies,'skills'=>$skills,));
	
	}
	
	
	public function actionSearch()
	{
        $flag = 1;
		$keyword = ($_POST['keyword']); // Get words to search
		$pieces = trim($keyword);
		$pieces = explode(" ", $pieces); // split words to search
		$count = sizeof($pieces); // get number of word to search
		$query = '';
		for($i = 0; $i < $count;$i++) // prepare query
		{
			if ($i == $count - 1){
				$query = $query.'name like \'%'.$pieces[$i].'%\'';
			} else {
				$query = $query.'name like \'%'.$pieces[$i].'%\' OR ';
			}

		}

		$criteria = new CDbCriteria;
		$criteria->condition = $query;
		$results = Array();

		if ($keyword != null){
			$skillsArray = Skillset::model()->findAll($criteria);
			foreach ($skillsArray as $sk)
			{
				if ($sk != null){
					$jobIds = JobSkillMap::model()->findAllByAttributes(array('skillid'=>$sk->id)); // get all jobs with that skill
					if ($jobIds != null) {
						foreach ($jobIds as $ji)
						{
							if ($ji != null){
								$jobid = $ji->jobid;
								$duplicate = 0;
								if (sizeof($results) > 0){
									foreach($results as $t){
										if ($t != null){
											if (strcmp($t->id,$jobid) == 0){
												$duplicate = 1;
											}
										}
									}
								}
									
								if ($duplicate == 0){
									$results[] = Job::model()->findByAttributes(array('id'=>$jobid, 'active'=>'1'));	// search for skill only
								}
							}
						}

					}
				}
			}
				
			

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

        $this->render('home',array('flag'=>$flag, 'results'=>$results,'user'=>$user,'companies'=>$companies,'skills'=>$skills,));
		//$this->render('studentSearchResults',array('results'=>$results,'user'=>$user,'companies'=>$companies,'skills'=>$skills,));

	}
 
	public function actionAdminSearch()
	{
		$keyword = $_POST['keyword']; // Get words to search
		$keyword = trim($keyword);
		$pieces = explode(" ", $keyword); // split words to search
		$count = sizeof($pieces); // get number of word to search
		$query = '';
		
		//print "<pre>"; print_r($count);print "</pre>";
		
		for($i = 0; $i < $count;$i++) // prepare query
		{
			if ($i == $count - 1){
				$query = $query.'username like \'%'.$pieces[$i].'%\'';
			} else {
				$query = $query.'username like \'%'.$pieces[$i].'%\' OR ';
			}

		}
		//print "<pre>"; print_r($query);print "</pre>";
		
		$criteria = new CDbCriteria;
		$criteria->condition = $query;
		$results = null;
		
		if ($keyword != null)
		{
			$results = User::model()->findAll($criteria);	
		}
		
		$query1 = '';
		for($i = 0; $i < $count;$i++) // prepare query
		{
			if ($i == $count - 1)
			{
				$query1 = $query1.'title like \'%'.$pieces[$i].'%\'';
			}
		    else
		    {
				$query1 = $query1.'title like \'%'.$pieces[$i].'%\' OR ';
			}

		}
		
		$criteria1 = new CDbCriteria;
		$criteria1->condition = $query1;
		$results1 = null;
		if ($keyword != null)
		{
			$results1 = Job::model()->findAll($criteria1);	
		}
		
		$username = Yii::app()->user->name;
		$user = User::model()->find("username=:username",array(':username'=>$username));
		
		$notification = Notification::model()->getNotificationId($user->id); // pass the notifications
		$this->render('adminhome', array('results'=>$results, 'results1'=>$results1, 'notification'=>$notification));
	
		
	}
	public function actionDisableUser()
	{
		$id = $_POST['id'];
		$modle = User::model()->find("id=:id",array(':id'=>$id));
		//print "<pre>"; print_r($modle->username);print "</pre>";
		$modle->disable = 1;
		$modle->save(false);
		$notification = Notification::model()->getNotificationId($modle->id);
		$this->actionAdminHome();
	}
	
	public function actionenableUser()
	{
		$id = $_POST['id'];
		$modle = User::model()->find("id=:id",array(':id'=>$id));
		//print "<pre>"; print_r($modle->username);print "</pre>";
		$modle->disable = 0;
		$modle->save(false);
		$this->redirect("/JobFair/index.php/home/adminhome");
	}
	
	public function actionDeleteJob()
	{
		$id = $_POST['id'];
		$modle = Job::model()->find("id=:id",array(':id'=>$id));
		$modle1 = JobSkillMap::model()->findAllByAttributes(array('jobid'=>$id));
		$modle2 = Application::model()->findAllByAttributes(array('jobid'=>$id));
		foreach ($modle1 as $job){
		$job->delete();}
		foreach ($modle2 as $job1){
		$job1->delete();}
		//print "<pre>"; print_r($modle->username);print "</pre>";
		$modle->delete();
		$this->redirect("/JobFair/index.php/home/adminhome");
	}
	
	public function actionDeleteNotification()
	{	
		$id = $_GET['id'];
		$modle = Notification::model()->find("id=:id",array(':id'=>$id));
		//print "<pre>"; print_r($modle->id);print "</pre>";
		$modle->delete();
		$username = Yii::app()->user->name;
		$user = User::model()->find("username=:username",array(':username'=>$username));
		if ($user->FK_usertype == 1)
		$this->redirect("/JobFair/index.php/home/studenthome");
		elseif ($user->FK_usertype == 2) 
		$this->redirect("/JobFair/index.php/home/employerhome");
		else 
		$this->redirect("/JobFair/index.php/home/adminhome");
	}	

	public function actionAcceptNotificationSchedualInterview()
	{	
		$id = $_GET['id'];
		//print "<pre>"; print_r($id);print "</pre>"; return;
		$modle = Notification::model()->find("id=:id",array(':id'=>$id));
		$modle->been_read = 1;
		//User::sendEmployerNotificationStudentAcceptIntervie($modle->receiver_id, $modle->sender_id);
		$modle->save(false);
		//SEND EMAIL NOTIFICATION
		$username = Yii::app()->user->name;
		$user = User::model()->find("username=:username",array(':username'=>$username));
		$message = "$username just accepted your interview invitation.";
		$recive = User::model()->findByPk($modle->sender_id);
		//$html = User::replaceMessage($recive->username, $message);
        User::sendEmail($recive->email, "Virtual Job Fair", "Interview Schedule Accepted", $message);
		//User::sendEmailEmployerAcceptingInterviewNotificationAlart($recive->email, $recive->username, $username, $message);
		if ($user->FK_usertype == 1)
		$this->redirect("/JobFair/index.php/home/studenthome");
		else 
		$this->redirect("/JobFair/index.php/home/employerhome");
	}
	
	public function actionAddSkill(){
		$skill = new Skillset;
		$skill->name = $_POST['skillname'];
		$skill->save();
		$this->redirect("/JobFair/index.php/home/adminhome");
	}
	
	public function actionMergeSkills(){
		$skillname1 = $_POST['skill1'];
		$skillname2 = $_POST['skill2'];
		$skill1 = Skillset::model()->find("name=:name", array(":name"=>$skillname1));
		$skill2 = Skillset::model()->find("name=:name", array(":name"=>$skillname2));
		
		$jobskills = JobSkillMap::model()->findAll("skillid=:skillid", array(":skillid"=>$skill2->id));
		$studentskills = StudentSkillMap::model()->findAll("skillid=:skillid", array(":skillid"=>$skill2->id));
		
		
		foreach($jobskills as $skill){
		if (JobSkillMap::model()->find("skillid=:skillid", array(":skillid"=>$skill1->id)) == null) {
				$skill->skillid = $skill1->id;
				$skill->save();
			} else {
				$skill->delete();
			}
		}
		foreach($studentskills as $skill){
			if (StudentSkillMap::model()->find("skillid=:skillid", array(":skillid"=>$skill1->id)) == null) {
				$skill->skillid = $skill1->id;
				$skill->save();
			} else {
				$skill->delete();
			}
			
		}
		$skill2->delete();
		$this->redirect("/JobFair/index.php/home/adminhome");
	}

    public function actionNotificationAdmin()
    {
        $results = null;
        $results1 = null;

        $username = Yii::app()->user->name;
        $user = User::model()->find("username=:username",array(':username'=>$username));

        $notification = Notification::model()->getNotificationId($user->id); // pass the notifications
        $matchnotification = MatchNotification::model()->findBySql("SELECT * FROM match_notification ORDER BY date_modified DESC limit 1");
        $status = Array('status'=>$matchnotification['status'], 'date_modified'=>$matchnotification['date_modified'], 'userid'=>$matchnotification['userid']);
        $user = User::model()->find("id=:id",array(':id'=>$matchnotification['userid']));
        $status['username'] = $user['username'];
        $this->render('notificationadmin', array('results'=>$results, 'results1'=>$results1, 'notification'=>$notification, 'matchnotification'=>$status));
    }


    // synchronize with the careerpath API
    public function actionCareerPathSync()
    {
        // using test URL retrieve mock json objects
        // here I would request a date range, since this script runs daily as a cron job
        //
        $request = Yii::app()->curl->run('http://www.json-generator.com/api/json/get/bRQiTpYSCq?indent=2');

        $job_postings = CJSON::decode($request->getData());

        // keep track of new jobs
        $new_jobs_count = 0;

        // check each object to see if it has been posted already:
        // criteria for duplicate jobs:
        // - same title, description and expiration date
        foreach ($job_postings as $job_posting)
        {
            // dissect scis job posting information
            $jp_id = $job_posting['ID'];
            $jp_postedTime = $job_posting['PostedTime'];
            $jp_expireTime = $job_posting['ExpireTime'];
            $jp_company = $job_posting['Company'];
            $jp_position = $job_posting['Position'];
            $jp_company_url = $job_posting['URL'];
            $jp_company_background = $job_posting['Background'];
            $jp_description = $job_posting['Description'];
            $jp_duties = $job_posting['Duties'];
            $jp_qualifications = $job_posting['Qualifications'];
            $jp_company_email = $job_posting['Email'];
            $jp_posted_by = $job_posting['PostedBy'];
            //$jp_posting_format = $job_posting['Format']; dont care about this, ask joshua

            // attempt to find user in database (by email) that corresponds to the job posting
            $user_found = User::model()->find('email=:jp_company_email', array(':jp_company_email' => $jp_company_email));

            // if  user not found in database, create a new 'dummy' user for this posting
            if (count($user_found) <= 0)
            {
                // user info (exclude first name and last name)
                $new_user = new User();
                $new_user->email = $jp_company_email;
                $new_user->activated = 1; // activate their account, and force them to retreive password (if they ever want to login)
                // generate username from email
                $user_name = str_replace(array('@', '.'), '_', $jp_company_email);
                $new_user->username = $user_name;
                $new_user->FK_usertype = 2; // employer type
                $new_user->registration_date = new CDbExpression('NOW()');
                $new_user->image_url = '/JobFair/images/profileimages/user-default.png';

                // hash the password before storing it into the database
                $hasher = new PasswordHash(8, false);
                $new_user->password = $hasher->HashPassword($new_user->password);

                // add user to db
                $new_user->save(false);

                // user company info
                $cmpny_info = new CompanyInfo();
                $cmpny_info->name = $jp_company;
                $cmpny_info->website = $jp_company_url;
                $cmpny_info->description = $jp_company_background;
                $cmpny_info->FK_userid = $new_user->id;
                // add company info to db
                $cmpny_info->save(false);

                // user basic info
                $basic_info = new BasicInfo();
                $basic_info->about_me = $jp_posted_by; // ask professor about this mapping
                $basic_info->userid = $new_user->id;
                $basic_info->hide_phone = 1;
                $basic_info->allowSMS = 0;
                $basic_info->validated = 1;
                // add basic info to db
                $basic_info->save(false);
            }

            // we have a user, post under his/her account
            $current_user = (isset($new_user) ? $new_user : $user_found);

            // check for duplicate postings
            $dup_entries = Job::model()->find(  "FK_poster=:poster AND ".
                "title=:title AND ".
                "deadline=:deadline AND ".
                "post_date=:post_date",
                array(  ':poster' => $current_user->id,
                    ':title' => $jp_position,
                    ':deadline' => date('Y-m-d H:i:s', strtotime($jp_expireTime)),
                    ':post_date' => $jp_postedTime));

            // duplicate entry, ignore
            if (count($dup_entries) > 0)
            {
                continue;
            }

            // no duplicates, add posting
            $new_job_posting = new Job();
            $new_job_posting->FK_poster = $current_user->id; // need an account
            $new_job_posting->post_date = $jp_postedTime;
            $new_job_posting->title = $jp_position;
            $new_job_posting->deadline = date('Y-m-d H:i:s', strtotime($jp_expireTime));
            $new_job_posting->description = $jp_description . $jp_duties . $jp_qualifications;
            $new_job_posting->type = 'CIS'; // know it was posted using this api
            $new_job_posting->compensation = ""; // not available from CIS
            $new_job_posting->posting_url = $jp_id;

            // post the job to db
            $new_job_posting->save(false);

            // skill match descripnt against database
            $decoded_desc = utf8_decode($new_job_posting->description);
            $decoded_desc = str_replace(array('/', ',', '.'), ' ', $decoded_desc);
            $description_words = explode(' ', $decoded_desc); // split into words
            $skill_order = 0;
            foreach ($description_words as $word)
            {
                // check database to see if current word is a skill
                $skill = Skillset::model()->find("name=:name", array(":name"=>$word));
                if ($skill)
                {
                    // its a skill, map it to this posting on database
                    $skill_map = new JobSkillMap();
                    $skill_map->jobid = $new_job_posting->id;
                    $skill_map->skillid = $skill->id;
                    $skill_map->ordering = $skill_order;
                    $skill_order++;
                    $skill_map->save(false);
                }
            }

            // all went good
            echo 'Success!';
        }

    }

}