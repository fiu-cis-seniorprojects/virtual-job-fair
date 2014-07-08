<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property integer $FK_usertype
 * @property string $email
 * @property string $registration_date
 * @property string $activation_string
 * @property integer $activated
 * @property string $image_url
 * @property string $first_name
 * @property string $last_name
 * @property boolean $hide_email
 * @property boolean $job_notification
 * @property boolean $looking_for_job
 * @property boolean $job_interest
 * @property boolean $job_int_date

 *
 * The followings are the available model relations:
 * @property Application[] $applications
 * @property BasicInfo $basicInfo
 * @property CompanyInfo $companyInfo
 * @property Education[] $educations
 * @property Experience[] $experiences
 * @property Job[] $jobs
 * @property Message[] messagesReceived
 * @property Message[] messagesSent
 * @property Rating[] $rating
 * @property SessionSubscriberMap[] $sessionSubscriberMaps
 * @property SessionSubscriberMap[] $sessionSubscriberMaps1
 * @property StudentSkillMap[] $studentSkillMaps
 * @property Usertype $fKUsertype
 * @property Profilefield[] $profilefields
 * @property VideoSession[] $videoSessions
 */
class User extends CActiveRecord
{
	public $password_repeat;
	public $skillrating;
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return User the static model class
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
        return 'user';
    }

    /**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('username, password, password_repeat, email, first_name,
					 last_name, hide_email', 'required'),
			array('username, email', 'unique'),
		    array('password', 'compare'),
			array('password_repeat', 'safe'),
		    array('email', 'email'),
			array('username, password, email, activation_string, first_name, last_name', 'length', 'max'=>45),
			array('image_url', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, username, password, FK_usertype, email, registration_date, activation_string, image_url, first_name, last_name', 'safe', 'on'=>'search'),
			array('image_url', 'file','types'=>'jpg, gif, png', 'allowEmpty'=>true, 'on'=>'update'),

			);
	}

	/**
	 * Validates user password
	 * @return boolean whether the password is valid
	 */
	public function validatePassword($password)
	{
		$hasher = new PasswordHash(8, false);
		return $hasher->CheckPassword($password, $this->password);
	}

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
    	//print "test";
        return array(
            'applications' => array(self::HAS_MANY, 'Application', 'userid'),
            'basicInfo' => array(self::HAS_ONE, 'BasicInfo', 'userid'),
        	'companyInfo' => array(self::HAS_ONE, 'CompanyInfo', 'FK_userid'),
            'educations' => array(self::HAS_MANY, 'Education', 'FK_user_id'),
        	'experiences' => array(self::HAS_MANY, 'Experience', 'FK_userid'),
            'jobs' => array(self::HAS_MANY, 'Job', 'FK_poster'),
        	'messagesReceived' => array(self::HAS_MANY, 'Message', 'FK_receiver'),
        	'messagesSent' => array(self::HAS_MANY, 'Message', 'FK_sender'),
            'rating' => array(self::HAS_MANY, 'Rating', 'FK_studentId'),
            'sessionSubscriberMaps' => array(self::HAS_MANY, 'SessionSubscriberMap', 'studentid'),
            'sessionSubscriberMaps1' => array(self::HAS_MANY, 'SessionSubscriberMap', 'sessionid'),
            'studentSkillMaps' => array(self::HAS_MANY, 'StudentSkillMap', 'userid', 'order'=>'ordering ASC'),
            'fKUsertype' => array(self::BELONGS_TO, 'Usertype', 'FK_usertype'),
            'profilefields' => array(self::MANY_MANY, 'Profilefield', 'user_profilefield_map(userid, fieldid)'),
            'videoSessions' => array(self::HAS_MANY, 'VideoSession', 'FK_host'),
            'resume' => array(self::HAS_ONE, 'Resume', 'id'),
        	'notifications' => array(self::HAS_MANY, 'Notification', 'receiver_id'),
        );
    }

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'username' => 'Username',
			'password' => 'Password',
			'password_repeat' => 'Re-type Password',
			'FK_usertype' => 'Fk Usertype',
			'email' => 'Email',
			'registration_date' => 'Registration Date',
			'activation_string' => 'Activation String',
			'image_url' => 'Image Url',
			'first_name' => 'First Name',
			'last_name' => 'Last Name',
			'hide_email' => 'Hide email from students?',
			'disable' => "Disable",
            'job_notification' => "Job Match Notifications",
            'looking_for_job' => "Searching for job"
		);
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
        $criteria->compare('username',$this->username,true);
        $criteria->compare('password',$this->password,true);
        $criteria->compare('FK_usertype',$this->FK_usertype);
        $criteria->compare('email',$this->email,true);
        $criteria->compare('registration_date',$this->registration_date,true);
        $criteria->compare('activation_string',$this->activation_string,true);
        $criteria->compare('activated',$this->activated);
        $criteria->compare('image_url',$this->image_url,true);
        $criteria->compare('first_name',$this->first_name,true);
        $criteria->compare('last_name',$this->last_name,true);
        $criteria->compare('job_notification',$this->job_notification,true);
        $criteria->compare('looking_for_job',$this->looking_for_job,true);
        $criteria->compare('job_interest', $this->job_interest, true);
        $criteria->compare('job_int_date', $this->job_int_date, true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    private static function constructEmailObject()
    {
        $mail = new YiiMailer();
        $mail->IsSMTP();
        $mail->Host = 'smtp.cs.fiu.edu';
        $mail->Port = 25;
        $mail->SMTPAuth = false;
        $mail->setView('contact');
        $mail->setLayout('mail');
        $mail->setFrom('virtualjobfair_no-reply@cs.fiu.edu', 'Virtual Job Fair');
        return $mail;
    }

    public static function sendEmail($to, $subject, $email_description, $message)
    {
        $email = self::constructEmailObject();
        $email->setTo($to);
        $email->setSubject($subject);
        $email->setData(array('message' => $message,
            'name' => 'Virtual Job Fair',
            'description' => $email_description));
        $email->send();
    }

//    public function sendVerificationEmail() {
//        $link = CHtml::link('click here', 'http://'.Yii::app()->request->getServerName() . '/JobFair/index.php/user/VerifyEmail?username=' . $this->username
//            . '&activation_string=' . $this->activation_string);
//        $address = $this->email;
//        $email = self::constructEmailObject();
//        $email->setData(array('message' => 'You need to verify your account before logging in.  Use this '. $link .' to verify your account.',
//                            'name' => 'Virtual Job Fair',
//                            'description' => 'Verify Account'));
//        $email->setTo($address);
//        $email->setSubject('Verify your account on Virtual Job Fair');
//        $email->send();
//    }

//	public static function sendEmailWithNewPassword($address, $password, $username) {
//    	$email = self::constructEmailObject();
//    	$link = CHtml::link('here', 'http://'. Yii::app()->request->getServerName()  . '/JobFair/' );
//    	$email->setTo($address);
//    	$email->setSubject('Your new password');
//        $email->setData(array('message' => '<br/>Username: '. $username .'<br/>Password: '. $password . '<br/>Login: '. $link,
//            'name' => 'Virtual Job Fair',
//            'description' => 'Password Reset'));
//    	$email->send();
//    }
//
//	public static function sendEmployerVerificationEmail($to) {
//        $modle = User::model()->findByPk($to);
//        $link= CHtml::link('here', 'http://'.Yii::app()->request->getServerName().'/JobFair/');
//        $message = "Your account has just been activated. Click $link to login";
//        $email = self::constructEmailObject();
//        $email->setData(array('message' => $message, 'name' => 'Virtual Job Fair', 'description' => 'Account has been activated'));
//        $email->setTo($modle->email);
//        $email->setSubject("Account activated on Virtual Job Fair");
//        $email->send();
//    }

//    public static function sendEmailNotificationAlart($address, $to, $from, $message) {
//    	$email = self::constructEmailObject();
//        $email->setTo($address);
//        $email->setSubject("Virtual Job Fair Application Submitted");
//        $email->setData(array('message' => $message, 'name' => 'Virtual Job Fair', 'description' => 'New Application Submitted'));
//        $email->send();
//    }
//
//
//    public static function sendEmailMessageNotificationAlart($address, $to, $from, $message) {
//        $email = self::constructEmailObject();
//        $email->setTo($address);
//        $email->setSubject("Virtual Job Fair Message");
//        $email->setData(array('message' => $message, 'name' => 'Virtual Job Fair', 'description' => 'Message from Virtual Job Fair'));
//        //$email->setBody($message);
//        $email->send();
//    }

//    public static function sendEmailEmployerAcceptingInterviewNotificationAlart($address, $to, $from, $message) {
//    	$email = self::constructEmailObject();
//        $email->setTo($address);
//        $email->setSubject("Your interview schedule was accepted");
//        $email->setData(array('message' => $message, 'name' => 'Virtual Job Fair', 'description' => 'Interview Schedule Accepted'));
//        //$email->setBody($message);
//        $email->send();
//    }

//    public static function sendEmailStudentNotificationVirtualHandshakeAlart($address, $to, $from, $message) {
//        $email = self::constructEmailObject();
//        $email->setTo($address);
//        $email->setSubject("A handshake from Virtual Job Fair");
//        $email->setData(array('message' => $message, 'name' => 'Virtual Job Fair', 'description' => 'Handshake Notification'));
//        //$email->setBody($message);
//        $email->send();
//    }


    public function isAStudent(){
    	return ($this->FK_usertype == 1);
    }

    public function isMatchNotificationSet(){
    	return ($this->job_notification == 1);
    }

    public function isLookingForJob(){
        return ($this->looking_for_job == 1);
    }

    public function isAEmployer(){
    	return ($this->FK_usertype == 2);
    }

    public function isAAdmin(){
    	return ($this->FK_usertype == 3);
    }


    public static function isCurrentUserStudent(){
    	$username = Yii::app()->user->name;
    	$user = User::model()->find("username=:username",array(':username'=>$username));
    	if ($user == null)
    		return false;
    	return ($user->FK_usertype == 1);
    }

	public static function isCurrentUserAdmin(){
    	$username = Yii::app()->user->name;
    	$user = User::model()->find("username=:username",array(':username'=>$username));
    	if ($user == null)
    		return false;
    	return ($user->FK_usertype == 3);
    }

	public static function isCurrentUserEmployer(){
    	$username = Yii::app()->user->name;
    	$user = User::model()->find("username=:username",array(':username'=>$username));
    	if ($user == null)
    		return false;
    	return ($user->FK_usertype == 2);
    }

    public static function isStudent($username){
    	$user = User::model()->find("username=:username",array(':username'=>$username));
    	if ($user == null)
    		return false;
    	return ($user->FK_usertype == 1);
    }

    public static function sendAllStudentVerificationAlart($id, $username, $email, $message, $link){


    	$students= User::model()->findAllByAttributes(array('FK_usertype'=>1));

    	foreach ($students as $student)

                {
                	$model=new Notification;
					$model->sender_id = $id;
					$model->receiver_id = $student->id;
                    date_default_timezone_set('America/New_York');
					$model->datetime = date('Y-m-d H:i:s');
					$model->been_read = 0;
					$model->importancy = 1;
					$model->message =  $message;//$username. " just join our website, check there jobpost and apply... ";
					$model->link = $link;
					$model->save(false);


                }


		return;
    }

    public static function sendSchedualNotificationAlart($sender, $reciver, $message, $link){

        $model = new Notification;
        $model->sender_id = $sender;
        $model->receiver_id = $reciver;
        $model->datetime = date('Y-m-d H:i:s');
        $model->been_read = 0;
        $model->message = $message;
        $model->importancy = 4;
        $model->link = $link;
        $model->save(false);

    }
    public static function sendEmployerNotificationAlart($sender, $reciver, $message, $link, $level){

    	$model = new Notification;
    	$model->sender_id = $sender;
    	$model->receiver_id = $reciver;
    	$model->datetime = date('Y-m-d H:i:s');
    	$model->been_read = 0;
    	$model->message = $message;
    	$model->link = $link;
    	$model->importancy = 6;
    	$model->save(false);

    }

    public static function sendUserNotificationMessageAlart($sender, $reciver, $link, $level){

    	$model = new Notification;
    	$model->sender_id = $sender;

    	$recive = User::model()->find("username=:username",array(':username'=>$reciver));
    	if ($recive != NULL)
    	{
    	$model->receiver_id = $recive->id;
    	$model->datetime = date('Y-m-d H:i:s');
    	$model->been_read = 0;
    	$model->link = $link;
    	//print "<pre>"; print_r($model->link);print "</pre>";return;
    	$model->message = 'you got a new message from '.$sender ;
    	$model->importancy = 3;
    	$model->save(false);
    	}

    }

    public static function sendUserNotificationHandshakeAlart($sender, $reciver, $link, $message){

    	$model = new Notification;
    	$model->sender_id = $sender;

    	$model->receiver_id = $reciver;
    	$model->datetime = date('Y-m-d H:i:s');
    	$model->been_read = 0;
    	$model->link = $link;
    	//print "<pre>"; print_r($model->link);print "</pre>";return;
    	$model->message = $message;
    	$model->importancy = 2;
    	$model->save(false);



    }


    public static function sendStudentNotificationMatchJobAlart($sender, $reciver, $link, $message){
    	$model = new Notification;
    	$model->sender_id = $sender;

    	$model->receiver_id = $reciver;
        date_default_timezone_set('America/New_York');
        $model->datetime = date('Y-m-d H:i:s');
    	$model->been_read = 0;
    	$model->link = $link;
    	//print "<pre>"; print_r($model->link);print "</pre>";return;
    	$model->message = $message;
    	$model->importancy = 2;
    	$model->save(false);

    }


    public static function sendAdminNotificationNewEmpolyer($employer, $admins, $link, $message)
    {
    	foreach ($admins as $admin)
    	{
    		$model = new Notification();
    		$model->sender_id = $employer->id;
    		$model->receiver_id = $admin->id;
    		$model->datetime = date('Y-m-d H:i:s');
    		$model->been_read = 0;
    		$model->link = $link;
    		//print "<pre>"; print_r($model->link);print "</pre>";return;
    		$model->message = $message;
    		$model->importancy = 1;
    		$model->save(false);
    	}


    }

public static function sendEmployerNotificationStudentAcceptIntervie($sender, $reciver){

    	$model = new Notification;
    	$student = User::model()->findByPk($sender);
    	$model->sender_id = $sender;
    	$model->receiver_id = $reciver;
    	$model->datetime = date('Y-m-d H:i:s');
    	$model->been_read = 0;
    	$model->message = "$student->username just accept the interview invitation";
    	$model->importancy = 4;
    	$model->link = $student->username;
    	$model->save(false);

    }
    public static function getCurrentUser(){
    	$username = Yii::app()->user->name;
    	$user = User::model()->find("username=:username",array(':username'=>$username));
    	return $user;
    }
    
    public static function getUser($userid){
    	$user = User::model()->findByPk($userid);
    	return $user;
    }
	
    public static function getUserName($userid){
    	$user = User::model()->findByPk($userid);
    	return $user->username;
    }
    
	public static function activeEmployer($id)
	{
		$user = user::model()->findByPk($id);
		$user->activated = 1;
		$user->save(false);
	}
    
	public static function replaceMessage($to, $message){
        $base = Yii::app()->basePath;
        $base = explode('/', $base);
        array_pop($base);
        $base = implode('/', $base);
		$file = fopen($base . "/email/index1.html", "r");
//	$file = fopen("/Applications/XAMPP/xamppfiles/htdocs/JobFair/email/index1.html", "r");
		$html = "";
		while(!feof($file))
		{
			$html .= fgets($file);
		}		
		$html = str_replace("%USER%", $to, $html);
		$html = str_replace("%MESSAGE%", $message, $html);
		return $html;
	}

}