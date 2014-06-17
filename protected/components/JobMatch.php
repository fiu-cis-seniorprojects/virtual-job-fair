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

    private function queryForSkill($skillid, $skillmap){
        foreach ($skillmap as $skill){
            if ($skill->skillid == $skillid){
                return $skill;
            }
        }
        return null;
    }

    private function compare_skills($jobskillmaps, $studentskillmaps){
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

}

