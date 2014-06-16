<?php

class VideoInterviewController extends Controller
{
	public function actionHome()
	{
		$this->render('home');
	}
	


	public function actionScheduleInterview(){
		$original_string = "qwertyuiopasdfghjklzxcvbnm123456789";
		$lastid = VideoInterview::model()->find(array('order'=>'id DESC'));
		if($lastid!=null)
        {
		    $key = $lastid->id + 1 . "VJFID" . rand(1,10000000);
		}
		else
        {
			$key = 1 . "VJFID" . rand(1,10000000);
		}
		$refid = Notification::model()->find(array('order'=>'id DESC'));

		$username = Yii::app()->user->name;

		$sender_id = User::model()->find("username=:username",array(':username'=>$username))->id;

		
		$student = $_POST['user_name'];
		$receiver = User::model()->find("username=:username",array(':username'=>$student));
		$link = $username;
		
		
		$model = new VideoInterview;
 		$model->attributes = $_POST['VideoInterview'];
 		$model->FK_employer = $sender_id;
 		$model->FK_student = $receiver->id;
 		$model->session_key= $key;
 		$model->notification_id = $refid->id + 2;
 		$model->save(false);
		
		$message = $username. " scheduled a video interview with you on: $model->date at: $model->time Good Luck!";

		User::sendSchedualNotificationAlart($sender_id, $receiver->id, $message, $link);

		//print "<pre>"; print_r($receiver_id);print "</pre>";return;

		
		//SAVE TO VIDEO INTERVIEW TABLE
 		$message2 = "You scheduled an interview with ".$student. " at ". $model->time. " on ". $model->date." Click here to go to the interview page.";
 		User::sendSchedualNotificationAlart($receiver->id, $sender_id, $message2, $student);
 		$message3 = "Hi $receiver->username, $username will like to interview you on:<br/>Date: $model->date<br/>Time: $model->time";
        User::sendEmail($receiver->email, "Virtual Job Fair", "Scheduled Interview", $message3);
 		//User::sendEmailNotificationAlart($receiver->email, $receiver->username, $username, $message3);
 		
 		$link= CHtml::link('here', 'http://'.Yii::app()->request->getServerName().'/JobFair/index.php/home/studenthome');
		$message4 = "$username scheduled an interview with you on:<br/>Date: $model->date<br/>Time: $model->time<br/>$link". " Click $link to go to the interview page";
		
 		
		//$html = User::replaceMessage($student, $message4);
        User::sendEmail($receiver->email, "Virtual Job Fair", "Scheduled Interview", $message4);
 		//User::sendEmailNotificationAlart($receiver->email, $receiver->username, $username, $message4);
 		$this->redirect("/JobFair/index.php/profile/student/user/".$student);
 		//print "<pre>"; print_r($key);print "</pre>";return;

	}

	
	
	public function actionstartInterview(){
		
		$view = $_GET['view'];
		$notificationRead = $_GET['notificationRead'];
		$usertype = $_GET['usertype'];
		$session = $_GET['session'] ;
		$me = $_GET['me'] ;
		
		$this->render('videointerview',array('view'=>$view,'notificationRead'=>$notificationRead,'usertype'=>$usertype,'session'=>$session,'me'=>$me));
		
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