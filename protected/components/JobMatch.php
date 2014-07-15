<?php

class JobMatch extends CApplicationComponent
{
    private $_model=null;

    public function setModel($jid)
    {
        $this->_model = Job::model()->findByPk($jid);
    }

    public function getModel()
    {
        if (!$this->_model)
        {
            return;
        }
        return $this->_model;
    }

    private function queryForSkill($skillid, $skillmap)
    {
        foreach ($skillmap as $skill)
        {
            if ($skill->skillid == $skillid)
            {
                return $skill;
            }
        }
        return null;
    }

    private function compare_skills($jobskillmaps, $studentskillmaps)
    {
        //first take out all irrelevant skills from the student
        foreach($studentskillmaps as $skill)
        {
            $studentskills[] = $skill->skillid;
        }

        foreach($jobskillmaps as $skill)
        {
            $jobskills[] = $skill->skillid;
        }

        if (!isset($studentskills) || !isset($jobskills))
        {
            return 0;
        }
        else
        {
            $studentskills = array_intersect($studentskills, $jobskills);
            $score =  (count($studentskills) / count($jobskills));
            $skilldifference = 1;
            foreach($studentskills as $skillid)
            {
                $studentSkillObject = $this->queryForSkill($skillid, $studentskillmaps);
                $jobSkillObject =  $this->queryForSkill($skillid, $jobskillmaps);
                $skilldifference += ($studentSkillObject->ordering - $jobSkillObject->ordering);
            }
            if ($skilldifference == 0)
            {
                $skilldifference ++;
            }
            $score -=  $skilldifference / 100;
            return $score;
        }
    }

    private function cmp($student1,$student2)
    {
        if ($student1->skillrating == $student2->skillrating)
            return 0;
        return ($student1->skillrating < $student2->skillrating) ? 1 : -1;
    }

    public function getJobStudentsMatch($jobid)
    {


        $students = User::model()->findAll("FK_usertype = 1 AND (disable IS NULL OR disable = 0) AND activated = 1");
        $job = Job::model()->findByPk($jobid);
        if ($job == null)
        {
            return -1;
        }
//        if ($job->FK_poster != User::getCurrentUser()->id)
//        {
//            return -1;
//        }

        if (!isset($job->jobSkillMaps) || (sizeof($job->jobSkillMaps) == 0))
        {
            return array('students'=>null);
        }

        foreach ($students as $student)
        {
            $student->skillrating = $this->compare_skills($job->jobSkillMaps, $student->studentSkillMaps);

        }
        //return;

        usort($students, Array('JobMatch', 'cmp'));
        $size = 3;

        foreach($students as $key => $student)
        {
            if ($student->skillrating <= 0){
                unset($students[$key]);
            }
        }
        while (isset($students[$size + 1]))
        {
            if ($students[$size]->skillrating == $students[$size + 1]->skillrating)
            {
                $size ++;
            }
            else
            {
                break;
            }
        }

        $students = array_slice($students, 0, $size + 1);
//        if ($job->matches_found != 1)
//        {
//            $job->matches_found = 1;
//            foreach($students as $student)
//            {
//                //SENDNOTIFICATION to each student, a job has been posted that matches your skills
//                $link = 'http://'.Yii::app()->request->getServerName().'/JobFair/index.php/job/view/jobid/'.$job->id;
//                $sender = User::model()->findByPk($job->FK_poster);
//                $message = "Hi ".$student->username.", the company ".$sender->username." just posted a job ".$job->title." that matches your skills";
//                User::sendStudentNotificationMatchJobAlart($sender->id, $student->id, $link, $message);
//                //SEND EMAIL NOTIFICATION
//            }
//        }
        //return;

        return $students;
    }

    public function getStudentMatchJobs($student_id, $jobs)
    {
        //$jobs = Job::model()->findAll("active = 1");
        $student = User::model()->findByPk($student_id);

        foreach ($jobs as $job)
        {
            $job->skillrating = $this->compare_skills($student->studentSkillMaps, $job->jobSkillMaps);

        }

        usort($jobs, Array('JobMatch', 'cmp'));
        foreach($jobs as $key => $job)
        {
            if ($job->skillrating == 0){
                unset($jobs[$key]);
            }
        }
        $jobs = array_slice($jobs, 0 , 6);
        return $jobs;
    }

    public function indeed($query, $city)
    {
        $loc = $city;
        $result = Array();

        // to call Indeed API
        require_once getcwd() . '/indeed/indeed.php';
        // Indeed publisher number 5595740829812660
        $client = new Indeed("5595740829812660");
        // parameters pass to indeed API
        $params = array(
            "q" => $query,                              // query from user
            "l" => $loc,                                // user location
            "limit" => 25,                              // Maximum number of results returned per query. Default is 10
            //"userip" => $_SERVER['REMOTE_ADDR'],        // user IP address
            "userip" => '0.0.0.0',        // user IP address
            //"useragent" => $_SERVER['HTTP_USER_AGENT']  // user browser
            "useragent" => "JobFair Search Bot 1.0"  // user browser
        );

        // search results from indeed.com
        $results = $client->search($params);
        // get array of jobs
        $result = $this->xmlToArray($results);

        // convert snippets to skills
        $snippets = array();
        $j = 0;

        // if there are results from indeed.com API
        if($result['totalresults'] >0)
        {
            if($result['totalresults'] == 1)
            {
                //print_r($result);die;
                $snippets[$j] = strtolower($result['results']['result']['snippet']);
                $snippets[$j] = utf8_decode($snippets[$j]);
                $snippets[$j] = iconv(mb_detect_encoding($snippets[$j], mb_detect_order(), true), "ISO-8859-1//IGNORE", $snippets[$j]);
                $result['results']['result']['snippet'] = '';
            }
            else
            {
                for ($i = 0; $i < count($result['results']['result']); $i++, $j++)
                {
                    $snippets[$j] = strtolower($result['results']['result'][$i]['snippet']);
                    $snippets[$j] = utf8_decode($snippets[$j]);
                    $snippets[$j] = iconv(mb_detect_encoding($snippets[$j], mb_detect_order(), true), "ISO-8859-1//IGNORE", $snippets[$j]);

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

    public function careerBuilder($query, $city)
    {
        require_once getcwd() . '/careerBuilder/cbapi.php';
        $results = careerBuilder\CBAPI::getJobResults($query, $city, "", "");
        // print_r($results);
        return $results;
    }

    public function xmlToArray($input, $callback = null, $recurse = false)
    {
        $data = ((!$recurse) && is_string($input))? simplexml_load_string($input, 'SimpleXMLElement', LIBXML_NOCDATA): $input;
        if($data instanceof \SimpleXMLElement) $data = (array)$data;
        if (is_array($data)) foreach ($data as &$item) $item = $this->xmlToArray($item, $callback, true);
        return (!is_array($data) && is_callable($callback))? call_user_func($callback, $data): $data;
    }

    public function customJobSearch($query = null, $city = null)
    {
        if($query != null)
        {
            $job =  Job::model()->findAllBySql("SELECT * FROM job WHERE MATCH(type,title,description,comp_name) AGAINST ('%".$query."%' IN BOOLEAN MODE) AND active = '1'");
            $indeed = $this->indeed($query, $city);
            if($indeed['totalresults'] == 0)
            {
                $indeed = Array();
            }
            $cb = $this->careerBuilder($query, $city);
            if($cb[0] == 0)
            {
                $cb = Array();
            }
            return array('careerpath'=>$job, 'indeed'=>$indeed, 'careerbuilder'=>$cb);
        }
    }

}

