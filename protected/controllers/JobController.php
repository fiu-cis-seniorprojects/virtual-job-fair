<?php
$flag = 0;
$saveQuery = "";

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
	
	public function actionHome($allWords = null, $phrase = null, $anyWord = null, $minus = null, $radioOption = null,
                $city = null){

        $flag = 2;
        $mi = false;
        $query = "";
        $job = Array();

        if( $allWords == "" &&  $phrase == "" &&  $anyWord == "" &&  $minus == "")
        {
            $job =  Job::model()->findAllBySql("SELECT * FROM job WHERE active = '1';");
        }
        if( $allWords == "" &&  $phrase == "" &&  $anyWord == "" &&  $minus != "")
        {
            $mi = true;
        }
        if(isset($phrase) && $phrase != "")
        {
            if(strpos($phrase, '"') !== false) { $query = $phrase." "; }    // contains ""
            else{ $query = "\"$phrase\""." "; }                             // add  ""
            //var_dump($query);die;
        }
        if(isset($allWords) && $allWords != "")
        {
            if(strpos($allWords, '+') !== false) { $query .= $allWords." "; } // contains +
            else { $query .= "+".str_replace(" ", ' +', $allWords)." "; }     // add +
            // var_dump($query);die;
        }
        if(isset($anyWord) && $anyWord != "")
        {
            if(strpos($anyWord, 'OR') !== false) { $query .= $anyWord." "; } // contains OR
            else { $query .= str_replace(" ", ' OR ', $anyWord)." "; }       // add OR
           // var_dump($query);die;
        }
        if(isset($minus) && $minus != "")
        {
            if(strpos($minus, '-') !== false) { $query .= $minus." "; } // contains -
            else { $query .= "-".str_replace(" ", ' -', $minus)." "; }     // add -
        }
        if($query != null && $mi == false)
        {
            //print_r($query); exit;
            $job =  Job::model()->findAllBySql("SELECT * FROM job WHERE MATCH(type,title,description,comp_name) AGAINST ('%".$query."%' IN BOOLEAN MODE) AND active = '1'");
        }

        // calling indeed function
        if(isset($radioOption) && $radioOption != "" && $mi == false)
        {
            $result = $this->indeed($query, $city);
            if($result['totalresults'] == 0) {$result = "";}
            $result2 = $this->careerBuilder($query, $city);
            if($result2[0] == 0) {$result2 = "";}
            // jobs -> careerPath, result -> Indeed, cbresults -> careerBuilder
            $this->render('home', array('jobs'=>$job,'result'=>$result, 'cbresults'=>$result2, 'flag'=>$flag));
        }
        else
        {
            $result = "";
            $result2 = "";
            $this->render('home', array('jobs'=>$job, 'result'=>$result, 'cbresults'=>$result2,  'flag'=>$flag));
        }
	}

    public function actionSaveQuery($allWords = null, $phrase = null, $anyWord = null, $minus = null, $city = null, $tagName = null)
    {
        $saveQuery = "";
        $loc = "";
        $tag = "";
        $suc = false;

        if(isset($city) && $city != ""){ $loc = $city;  }
        else{$loc = ""; }

        if(isset($tagName) && $tagName != "" && strlen($tagName) < 25) { $tag = $tagName; }

        if(isset($phrase) && $phrase != "")
        {
            if(strpos($phrase, '"') !== false) { $saveQuery = $phrase." "; }    // contains ""
            else{ $saveQuery = "\"$phrase\""." "; }                             // add  ""
        }
        if(isset($allWords) && $allWords != "")
        {
            if(strpos($allWords, '+') !== false) { $saveQuery .= $allWords." "; } // contains +
            else { $saveQuery .= "+".str_replace(" ", ' +', $allWords)." "; }     // add +
        }
        if(isset($anyWord) && $anyWord != "")
        {
            if(strpos($anyWord, 'OR') !== false) { $saveQuery .= $anyWord." "; } // contains OR
            else { $saveQuery .= str_replace(" ", ' OR ', $anyWord)." "; }       // add OR
        }
        if(isset($minus) && $minus != "")
        {
            if(strpos($minus, '-') !== false) { $saveQuery .= $minus." "; } // contains +
            else { $saveQuery .= "-".str_replace(" ", ' -', $minus)." "; }     // add +
        }

        if( $saveQuery != "")
        {
            $username = Yii::app()->user->name;
            $model = User::model()->find("username=:username",array(':username'=>$username));
            $saved_queries = new SavedQuery();
            $saved_queries->query = htmlentities($saveQuery);
            $saved_queries->query_tag = $tag;
            $saved_queries->FK_userid = $model->id;
            $saved_queries->location = $loc;
            try{
                $saved_queries->save(false);
                $suc = 1;
            }catch (Exception $e)
            {
                $suc = 0;
               //alert("Oops, something went wrong. Please try again.");
            }
         }

        $this->render('savedQuerySuc', array('mesg'=>$suc));
        //$this->actionsavedQuerySuc($suc);
    }

//    public function actionsavedQuerySuc($suc)
//    {
//        $this->render('savedQuerySuc', array('mesg'=>$suc));
//    }

    public function careerBuilder($query, $city)
    {
        require_once 'protected/careerBuilder/cbapi.php';
        $results = careerBuilder\CBAPI::getJobResults($query, $city, "", "");
       // print_r($results);
        return $results;
    }


    // call to indeed API
    public function indeed($query, $city)
    {
        $loc = $city;
        $result = Array();

        // to call Indeed API
        require 'protected/indeed/indeed.php';
        // Indeed publisher number 5595740829812660
        $client = new Indeed("5595740829812660");

        // parameters pass to indeed API
        $params = array(
            "q" => $query,                              // query from user
            "l" => $loc,                                // user location
            "limit" => 25,                              // Maximum number of results returned per query. Default is 10
            "userip" => $_SERVER['REMOTE_ADDR'],        // user IP address
            "useragent" => $_SERVER['HTTP_USER_AGENT']  // user browser
        );

        // search results from indeed.com
        $results = $client->search($params);
        // get array of jobs
        $result = $this->xmlToArray($results);

        // convert snippets to skills
        $snippets = array();
        $j = 0;

        // if there are results from indeed.com API
        if($result['totalresults'] > 0){
            if($result['totalresults'] == 1)
            {
               //print_r($result);die;
               $snippets[$j] = strtolower($result['results']['result']['snippet']);
               $snippets[$j] = utf8_decode($snippets[$j]);
               //$snippets[$j] = iconv(mb_detect_encoding($snippets[$j], mb_detect_order(), true), "ISO-8859-1//IGNORE", $snippets[$j]);
               $result['results']['result']['snippet'] = '';
            }
            else{
                for ($i = 0; $i < count($result['results']['result']); $i++, $j++)
                {
                    $snippets[$j] = strtolower($result['results']['result'][$i]['snippet']);
                    $snippets[$j] = utf8_decode($snippets[$j]);
                    //$snippets[$j] = iconv(mb_detect_encoding($snippets[$j], mb_detect_order(), true), "ISO-8859-1//IGNORE", $snippets[$j]);

                    $result['results']['result'][$i]['snippet'] = '';
                }
            }

            if($result['totalresults'] == 1)
            {
                // put back into results snippet as skill words
                // check each snipped for skills
                    $cur_snippet = $snippets[0];
                    $cur_snippet = str_replace(array( '.', '/', ',', '.'), ' ', $cur_snippet);
                    $cur_snippet_words = explode(' ', $cur_snippet); // split into words
                    foreach ($cur_snippet_words as $snippet_word)
                    {
                        // check database to see if current word is a skill
                        $skill = Skillset::model()->find("LOWER(name)=:name", array(":name"=>$snippet_word));
                        if ($skill)
                        {
                                 $cur_skills = strtolower($result['results']['result']['snippet']);
                                if (!strstr($cur_skills, $snippet_word))
                                {
                                    $result['results']['result']['snippet'] .= ucfirst($snippet_word) . ' ';
                                }

                        }
                    }
            }
            else
            {
                // put back into results snippet as skill words
                for ($i = 0; $i < count($result['results']['result']); $i++)
                {
                    // check each snipped for skills
                    $cur_snippet = $snippets[$i];
                    $cur_snippet = str_replace(array( '.', '/', ',', '.'), ' ', $cur_snippet);
                    $cur_snippet_words = explode(' ', $cur_snippet); // split into words
                    foreach ($cur_snippet_words as $snippet_word)
                    {
                        // check database to see if current word is a skill
                        $skill = Skillset::model()->find("LOWER(name)=:name", array(":name"=>$snippet_word));
                        if ($skill)
                        {
                            // append current word (skill) to results snippet (check duplicates)
                                $cur_skills = strtolower($result['results']['result'][$i]['snippet']);
                                if (!strstr($cur_skills, $snippet_word))
                                {
                                    $result['results']['result'][$i]['snippet'] .= ucfirst($snippet_word) . ' ';
                                }
                        }
                    }

                }
            }
        }
        return $result;
   }

    // convert XML feed from Indeed to Array
    public function xmlToArray($input, $callback = null, $recurse = false)
    {
        $data = ((!$recurse) && is_string($input))? simplexml_load_string($input, 'SimpleXMLElement', LIBXML_NOCDATA): $input;
        if($data instanceof \SimpleXMLElement) $data = (array)$data;
        if (is_array($data)) foreach ($data as &$item) $item = $this->xmlToArray($item, $callback, true);
        return (!is_array($data) && is_callable($callback))? call_user_func($callback, $data): $data;
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
            $model->comp_name = CompanyInfo::getCompanyNamesUser(User::getCurrentUser()->id);
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
        User::sendEmail($poster->email, "Virtual Job Fair Application Submitted", "New Application Submitted", $message1);
		//User::sendEmailNotificationAlart($poster->email, $poster->username, $user->username ,$message1);
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
			//$html = User::replaceMessage($student->username, $message1);
            User::sendEmail($student->email, "A handshake from Virtual Job Fair", "Handshake Notification", $message1);
			//User::sendEmailStudentNotificationVirtualHandshakeAlart($student->email, $student->username, User::getCurrentUser()->username ,$message1);
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
					  		'viewApplication', 'Close', 'Search', 'SaveQuery'),
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
        // flag to display results in home
        $flag = 2;
        $bool = false;
        $keyword = "";
        $result = Array();
        // words to search for
        if(isset($_GET['keyword']))
        {
            $keyword = ($_GET['keyword']);
        }
        // array to contain the results of the search
        $results = Array();
        $result2 = Array();

        // there are words to search
        if ($keyword != null)
        {
            // operators for boolean search mode
            if(preg_match('/("|-)/', $keyword))
            {
                $bool = true;
            }

            if($bool == true)
            {
                // search FULLTEXT IN BOOLEAN MODE to allow for operations such as ' ""'  and ' - '
                $results =  Job::model()->findAllBySql("SELECT * FROM job WHERE MATCH(type,title,description,comp_name) AGAINST ('%".$keyword."%' IN BOOLEAN MODE) AND active = '1';");

            }
            if($bool == false)
            {// search FULLTEXT IN NATURAL LANGUAGE MODE
                $results =  Job::model()->findAllBySql("SELECT * FROM job WHERE MATCH(type,title,description,comp_name) AGAINST ('%".$keyword."%' IN NATURAL LANGUAGE MODE) AND active = '1';");
                //print_r ($result);
            }

            // location will be set to "Miami, Florida"
            $loc = "Miami, Florida";
            // call indeed API to get jobs query by user
            $result = $this->indeed($keyword, $loc);
            if($result['totalresults'] == 0) {$result = "";}
            $result2 = $this->careerBuilder($keyword, $loc);
            if($result2[0] == 0) {$result2 = "";}

        }

        // get user
        if (isset($_GET['user'])){
            $username = $_GET['user'];
        } else {
            $username = Yii::app()->user->name;
        }
        // pass user
        $user = User::model()->find("username=:username",array(':username'=>$username));
        // pass skills
        $skills = Skillset::getNames();
        // pass companies
        $companies = CompanyInfo::getNames();

        //print_r($result); return;

        // render search results, user, skills, companies and flag to job/home
        $this->render('home',array('result'=>$result, 'cbresults'=>$result2, 'jobs'=>$results,'user'=>$user,
            'companies'=>$companies,'skills'=>$skills,'flag'=>$flag));

    }


}