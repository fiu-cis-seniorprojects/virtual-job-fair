<?php
class MessageController extends Controller
{
	
	public function actionIndex($target = null)
	{
		$user = User::model()->findByAttributes(array('username'=>Yii::app()->user->name));
				
				
		$this->render('view', array('user'=>$user, 'target'=>$target)); //'deletedMessages'=>$deletedMessages));
	}	
		
	public function actionSend($username = null, $reply=null, $selfReply = null)
	{
		$user = new User;
		$model = new Message;
		$message = null;		
		
		$users = array();
		$models = User::model()->findAll(array('condition'=>'FK_usertype = 1'));
		
		foreach($models as $aUser)
		{
			$users[] = array(
					'label'=>CHtml::image($aUser->image_url, '', array('width'=>'20px')) . '  ' . $aUser->first_name . ' ' . $aUser->last_name,
					'value'=>"\"" .$aUser->first_name . " ".$aUser->last_name."\" <" .$aUser->username . ">"
			);
		}
				 
		if (isset($_POST['Message']))
		{
			$model->attributes = $_POST['Message'];
			
			
				$model->FK_sender = Yii::app()->user->name;
                date_default_timezone_set('America/New_York');
                $model->date = date('Y-m-d H:i:s');
				$model->userImage = $model->fKSender->image_url;

				$model->subject = $_POST['Message']['subject'];
				
				$receivers = $this->getReceivers($_POST["receiver"]);
				$receiverCount = count($receivers);
				
				for ($i = 0; $i < $receiverCount; $i++)
				{
					$model->FK_receiver = $receivers[$i];
					if (User::model()->find("username=:username",array(':username'=>$model->FK_receiver)) != null)
						$model->save();	

					$model = new Message;
					$model->attributes = $_POST['Message'];						
					$model->FK_sender = Yii::app()->user->name;
					$model->date = date('Y-m-d H:i:s');
					$model->userImage = $model->fKSender->image_url;					
					$model->subject = $_POST['Message']['subject'];
				}				

				User::sendUserNotificationMessageAlart(Yii::app()->user->id, $model->FK_receiver, 'http://'.Yii::app()->request->getServerName().'/JobFair/index.php/message', 3);
				$link= CHtml::link('here', 'http://'.Yii::app()->request->getServerName().'/JobFair/index.php/message');
				$recive = User::model()->find("username=:username",array(':username'=>$model->FK_receiver));
				if ($recive != NULL){
                    $message = "You just got a message from $model->FK_sender<br/> '$model->message'<br/> Access the message $link";
                    //$html = User::replaceMessage($recive->username, $message);
                    User::sendEmail($recive->email, "Virtual Job Fair Message", "Message from Virtual Job Fair", $message);
                    //User::sendEmailMessageNotificationAlart($recive->email, $recive->username, $model->FK_sender, $message);
				}
				$this->redirect("/JobFair/index.php/message");
				return;
				
		}
		
		if ($reply != null)
		{
			$message = Message::model()->findByPK($reply);
			
			if (Yii::app()->user->name == $message->FK_sender)
			   $username = $message->FK_receiver;
			else
			   $username = $message->FK_sender;
			
			$model->subject = $message->subject;
			$model->message = "\n\n\nOn " . $message->date . ", " . $message->FK_sender . " wrote:\n" . $message->message;
		}	
		
		$this->render('send', array('user'=>$user, 'users'=>$users, 'model'=>$model, 'username'=>$username));		
	}

    public function actionToggleMatchNotifications()
    {
        if(User::isCurrentUserAdmin())
        {
            $bit = intval($_GET['value']);
            $bit = ($bit == 0) ? 1 : 0;
            $mod = new MatchNotification();
            $mod->status = $bit;
            $mod->date_modified = date('Y-m-d H:i:s');
            $userinfo = User::getCurrentUser();
            $mod->userid = $userinfo['id'];
            $mod->save();
            $userid = $mod->getUserId();
            $user = User::model()->find("id=:id",array(':id'=>$userid));
            $state = ($mod->isGlobalNotificationOn()) ? '1' : '0';
            $data = Array('userid'=>$userid, 'status'=>$state, 'last_modified'=>$mod->getLastDate(), 'username'=>$user['username']);
            echo CJSON::encode($data);
        }
    }

    public function actionCheckNotificationState()
    {
        if(User::isCurrentUserAdmin())
        {
            $matchnotification = MatchNotification::model()->findBySql("SELECT * FROM match_notification ORDER BY date_modified DESC limit 1");
            $status = Array('status'=>$matchnotification['status'], 'date_modified'=>$matchnotification['date_modified'], 'userid'=>$matchnotification['userid']);
            $user = User::model()->find("id=:id",array(':id'=>$matchnotification['userid']));
            $status['username'] = $user['username'];
            echo CJSON::encode($status);
        }
    }
	
	//Ajax calls
	public function actionGetInbox()
	{
		$username = Yii::app()->user->name;
		$user = User::model()->find("username=:username",array(':username'=>$username));
		$messages = array();
		foreach ($user->messagesReceived(array('order'=>'id DESC')) as $aMessage)
		{
			if ($aMessage->been_deleted != 1)
			   $messages[] = $aMessage;
		}
		
		print CJSON::encode($messages);			
	}
	
	public function actionGetMessage($id)
	{
		$message = Message::model()->findByPK($id);
		print CJSON::encode($message);
	}
	
	public function actionGetSent()
	{
		$username = Yii::app()->user->name;
		$user = User::model()->find("username=:username",array(':username'=>$username));
		$messages = array();
		foreach ($user->messagesSent(array('order'=>'id DESC')) as $aMessage)
		{
			if ($aMessage->been_deleted != 1)
				$messages[] = $aMessage;
		}
		
		print CJSON::encode($messages);
	}
	
	public function actionGetTrash()
	{
		$username = Yii::app()->user->name;
		$user = User::model()->find("username=:username",array(':username'=>$username));
		$messages = array();
		foreach ($user->messagesSent(array('order'=>'id DESC')) as $aMessage)
		{
			if ($aMessage->been_deleted == 1)
				$messages[] = $aMessage;
		}
		
		foreach ($user->messagesReceived(array('order'=>'id DESC')) as $aMessage)
		{
			if ($aMessage->been_deleted == 1)
				$messages[] = $aMessage;
		}
		
		print CJSON::encode($messages);
	}
	
	public function actionSetAsRead($id)
	{
		$message = Message::model()->findByPk($id);
		$message->been_read = 1;
		$message->save();
	}
	
	public function actionSentToTrash()
	{
		$ids = $_REQUEST['messages'];
		foreach ($ids as $id)
		{
			$theId = intval($id);
			$message = Message::model()->findByPK($theId);
			$message->been_deleted = 1;
			$message->save(false);
		}
	}
	
	public function actionDeleteMessage()
	{
		$ids = $_REQUEST['messages'];
		foreach ($ids as $id)
		{
			$theId = intval($id);
			Message::model()->deleteByPK($theId);			
		}		
	}
	
	public function actionDeleteMessages()
	{
		$ids = $_REQUEST['messages'];
		foreach ($ids as $id)
		{
			$theId = intval($id);
			Message::model()->deleteByPK($theId);
		}
	}
	
	public function actionAutoComplete()
	{
		$users = array();
		
		if (isset($_GET['term'])) {
		
		   $models = User::model()->findAll();
		
		   foreach($models as $aUser)
		   {
			  $users[] = array(
			      'name'=>$aUser->username,
				  'label'=>$aUser->username,
				  'id'=>$aUser->id,
		      );
		   }
		}

		print "<pre>"; print_r($users);print "</pre>";return;
		print CJSON::encode($users);
	}
	
	private function getReceivers($string)
	{
		$receivers = array();
		$startPos = strpos($string, "<");
		while ($startPos !== false)
		{
			$endPos = strpos($string, ">");
			$receivers[] = substr($string , $startPos + 1, $endPos - $startPos - 1);
			
			$string = substr($string , $endPos + 2);
			
			if($string)
			  $startPos = strpos($string, "<");
			else
			  $startPos = false;
		}
		return $receivers;
	}
	
	
	
	
	//Specifies access rules
	public function accessRules()
	{
		return array(
				array('allow',  // allow authenticated users to perform these actions
						'actions'=>array('Index', 'Send', 'getInbox', 'getMessage', 'getSent',
								'setAsRead', 'sentToTrash', 'getTrash', 'deleteMessage', 'deleteMessages',
								'autoComplete', 'toggleMatchNotifications', 'checkNotificationState'),
						'users'=>array('@')),
				array('deny', //deny all users anything not specified
						'users'=>array('*'),
						'message'=>'Access Denied.'),
		);
	}
	
	
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
				'accessControl',
		);
	}
}