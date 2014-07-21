<?php

/**
 * This is the model class for table "job".
 *
 * The followings are the available columns in table 'job':
 * @property integer $id
 * @property string $type
 * @property string $title
 * @property integer $FK_poster
 * @property string $post_date
 * @property string $deadline
 * @property string $description
 * @property string $compensation
 * @property string $other_requirements
 * @property integer $email_notification
 * @property string $posting_url
 * @property string $comp_name
 *
 * The followings are the available model relations:
 * @property Application[] $applications
 * @property User $fKPoster
 * @property JobSkillMap[] $jobSkillMaps
 */
class Job extends CActiveRecord
{
	public $skillrating;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Job the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'job';
	}
	
	public static function getJobById($jobid)
	{
		$job = Job::model()->findByPk($jobid);
		
		return $job;
	}
	//Get jobs by skills
	public static function getJobsBySkill($skill_name, $comp_name)
	{
		// 		print "<pre>"; print_r($comp_id->attributes);print "</pre>";return;
		$jobMap = null;
		$skill_id = null;
		$comp_posts = null;
		
		// Query dabase by skill name and retrieve the skill_id
		$skill = Skillset::model()->findByAttributes(array('name'=>$skill_name));
		if ($skill != null){
			$skill_id = $skill->id;
			// Get all jobs that have the skill_id
			$jobMap = JobSkillMap::model()->findAllByAttributes(array('skillid'=>$skill_id));
		}
		
		// Query the database by Company name and get All job posts for that company
		$comp_id = CompanyInfo::model()->findByAttributes(array('name'=>$comp_name));
		if ($comp_id != null){
			$comp_posts = Job::model()->findAllByAttributes(array('FK_poster'=>$comp_id->FK_userid));
		}
		
		// Array of jobs
		$jobs = array();
		if ($jobMap != null){
			foreach ($jobMap as $aJobMap)
			{			
				$jobid = $aJobMap->jobid;
				if ($skill_id != null && $comp_posts != null) // search for Company and Skill
					$jobs[] = Job::model()->findByAttributes(array('id'=>$jobid, 'FK_poster'=>$comp_id->FK_userid));
				elseif ($comp_id == null)
					$jobs[] = Job::model()->findByAttributes(array('id'=>$jobid));	// search for skill only
			}
		} else {
			if ($comp_posts != null)
				foreach ($comp_posts as $aPost)
				{
					$tmp = $aPost->id;
					$jobs[] = Job::model()->findByAttributes(array('id'=>$tmp)); // search for Company only	
				}			
		}
		
		return $jobs;
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('type, title, FK_poster, post_date, description, deadline', 'required'),
			array('FK_poster, email_notification', 'numerical', 'integerOnly'=>true),
			array('type, title, compensation', 'length', 'max'=>45),
			array('deadline, other_requirements', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, type, title, FK_poster, post_date, deadline, description, compensation, other_requirements, email_notification, posting_url, comp_name', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'applications' => array(self::HAS_MANY, 'Application', 'jobid'),
			'fKPoster' => array(self::BELONGS_TO, 'User', 'FK_poster'),
			'jobSkillMaps' => array(self::HAS_MANY, 'JobSkillMap', 'jobid', 'order'=>'ordering ASC'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'type' => 'Type',
			'title' => 'Title',
			'FK_poster' => 'Fk Poster',
			'post_date' => 'Post Date',
			'deadline' => 'Deadline',
			'description' => 'Description',
			'compensation' => 'Compensation',
			'other_requirements' => 'Other Requirements',
			'email_notification' => 'Email Notification',
            'comp_name' => 'Company Name'
		);
	}
	
	public static function hasHandshake($jobid, $employerid, $studentid){
		$handshake = Handshake::model()->find("jobid=:jobid AND employerid=:employerid AND studentid=:studentid", array( ":jobid"=>$jobid, ":employerid"=> $employerid, ":studentid"=>$studentid));
		if ($handshake != null) {
			return "disabled";
		} else {
			return "";
		}
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('FK_poster',$this->FK_poster);
		$criteria->compare('post_date',$this->post_date,true);
		$criteria->compare('deadline',$this->deadline,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('compensation',$this->compensation,true);
		$criteria->compare('other_requirements',$this->other_requirements,true);
		$criteria->compare('email_notification',$this->email_notification);
        $criteria->compare('comp_name',$this->comp_name);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public static function queryForSkill($skillid, $skillmap){
		foreach ($skillmap as $skill){
			if ($skill->skillid == $skillid){
				return $skill;
			}
		}
		return null;
	}


    public function cascade_delete()
    {
        $id = $this->id;

        // remove any skill mappings
        $skill_mappigns = JobSkillMap::model()->findAllByAttributes(array('jobid' => $id));
        foreach ($skill_mappigns as $skill_mapping)
        {
            $skill_mapping->delete();
        }

        // remove any applications mappings
        $app_mappings = Application::model()->findAllByAttributes(array('jobid' => $id));
        foreach ($app_mappings as $app_mapping)
        {
            $app_mapping->delete();
        }

        // remove any handshake mappings
        $hs_mappings = Handshake::model()->findAllByAttributes(array('jobid' => $id));
        foreach($hs_mappings as $hs_mapping)
        {
            $hs_mapping->delete();
        }

        // finally remove job
        $this->delete();
    }
	
	public static function compare_skills($jobskillmaps, $studentskillmaps){
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
				$studentSkillObject = Job::queryForSkill($skillid, $studentskillmaps);
				$jobSkillObject =  Job::queryForSkill($skillid, $jobskillmaps);
			}
			if ($skilldifference == 0) {
				$skilldifference ++;
			}
			$score /= $skilldifference;
			return $score;
		}
	
	}
	
	public static function getMatchingJobs(){
		$jobs = Job::model()->findAll("active = 1");
		$student = User::getCurrentUser();
	
		foreach ($jobs as $job){
			$job->skillrating = Job::compare_skills($student->studentSkillMaps, $job->jobSkillMaps);
	
		}
		//return;
		function cmp($job1,$job2) {
			if ($job1->skillrating == $job2->skillrating)
				return 0;
			return ($job1->skillrating < $job2->skillrating) ? 1 : -1;
		}
	
		usort($jobs, 'cmp');
		foreach($jobs as $key => $job){
			if ($job->skillrating == 0){
				unset($jobs[$key]);
			}
		}
		
		
		$jobs = array_slice($jobs, 0 , 6);
		return $jobs;
	}

    public static function getJobTitle() {

        $jobTitle = Job::model()->findAll();
        $title = array();

        if ($jobTitle != null)
            foreach ($jobTitle as $jt)
                $title[] = $jt->title;

        return $title;

    }

    public static function getJobBySkill()
    {
        $skill = Skillset::model()->findAll();
        $skills = array();
        if($skill != null)
        {
            foreach($skill as $jk)
            {
                $skills[] = $jk->name;
            }
        }

        return $skills;
    }


}