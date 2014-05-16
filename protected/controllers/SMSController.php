<?php

class SMSController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update','Sendsms','GetAutoComplete','Verify','SendCode','Validation','ChangeSMSpref'),
				'users'=>array('@'),
			),
				
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionSendsms()
	{
		
		$username = Yii::app()->user->name;
		$model = User::model()->find("username=:username",array(':username'=>$username));
		$info = BasicInfo::model()->find("userid=:userid",array('userid'=>$model->id));
		$SMS = new SMS();
	
		
		
		if(User::isCUrrentUserStudent() && $info->validated == 1)
		{   
			
			$this->render('verified',array(
					'info'=>$info
			));
			return;
		}
		
		if($info->validated == 0) // user needs a validated number before sending and receiving messages
		{
			$this->render('validatephone',array(
					'info'=>$info,'model'=> $model
			));
			
			return;
			
			
		}
		
		
		
		if($model->FK_usertype == 1)  // student type
		{
		$id = SMS::model()->findByAttributes(array('receiver_id'=>$model->id));
		}
		
		else{                         // employer
			$id = SMS::model()->findByAttributes(array('sender_id'=>$model->id));
		}
		
		
	
		
		if(isset($_POST['SMS']) && isset($_POST['username']) )
		{
            if($this->actionVerify() != "")
            {
                 return;

            }

			$SMS->Message = "VJF Message from " . $username . " \n";
			
			//$SMS->attributes = $_POST['SMS'];
			$SMS->sender_id = $model->id;
			$receiver = User::model()->find("username=:username",array(':username'=>$_POST['username']));
			$SMS->receiver_id = $receiver->id;
			$SMS->date = new CDbExpression('NOW()');
			$SMS->Message .= $_POST['SMS']['Message'];
			$SMS->save(false);
			
			
			Yii::log("the id = ".$receiver->id, CLogger::LEVEL_ERROR, 'application.controller.Prof');
			$model1 = BasicInfo::model()->find("userid=:userid",array('userid'=>$receiver->id)); //get the receivers phone numbers
			$phone = $model1->phone;
			

			spl_autoload_unregister(array('YiiBase','autoload'));
			require('Services/Twilio.php');
			$sid = "AC1a9ec3e5aaf3135a5e4893c095be8430";
			$token = "15871d8b55c402145f12c77dd7525644";
			$client = new Services_Twilio($sid, $token);
			
			$client->account->messages->sendMessage("+17868375870", "+1".$phone  ,$SMS->Message );
			spl_autoload_register(array('YiiBase','autoload'));
			$SMS = new SMS();
			
		}
		
		
		//Yii::log("the user name is", CLogger::LEVEL_ERROR, 'application.controller.Prof');
		
		
		
		$this->render('sendsms',array(
			'info'=> $info,'SMS'=>$SMS
		));
			
		
	}
	
	
	
	//returns ajax for the username field 
	public function actionGetAutoComplete(){
		
		
		$term = $_GET['term'];
		
		$models = User::model()->findAll(array('condition'=>'username LIKE :username AND username != "admin"','params'=>array(':username'=>"$term%")));
		
		
		
		
		
		$result = array();
		foreach($models as $userName)
		{
			$result[] = $userName->username;
		
		}
		
		
		echo CJSON::encode($result);
	}

	
	public function actionVerify(){
		
		
		$receiver = User::model()->find("username=:username",array(':username'=>$_POST['username']));
		$exists = false;;
		if($receiver)
		{
			$model1 = BasicInfo::model()->find("userid=:userid",array('userid'=>$receiver->id));
			$exists = true;
		}
		
		
		$username = $_POST['username'];
		$body = $_POST['SMS']['Message'];
		$msg = "";
		
		
		if(strlen($body) > 190)
		{
			$msg .= "The message is too big. Please create a message with less than 160 characters <br />";
		}
		
		if(strlen($body) == 0)
		{
			$msg .= "Please enter a message to be sent. No blank messages allowed. <br />";
		}
		
		if(!$receiver)
		{
			$msg .= "User does not exist. <br />";
		}
		
		if($exists && !$model1->phone)
		{
			$msg .= "User does not have a phone number in record. <br />";
		}
		if($receiver && $model1->validated == 0)
		{
			$msg .= "User has not set up the SMS functionality. <br />";
		}
		
		if($receiver && $model1->allowSMS == 0)
		{
			$msg .= "User does not allow to be contacted by SMS. <br />";
		}

		
		
		echo $msg;
	}
	
	
	public function actionValidation()
	{
		$username = Yii::app()->user->name;
		$model = User::model()->find("username=:username",array(':username'=>$username));
		$info = BasicInfo::model()->find("userid=:userid",array('userid'=>$model->id));
		
		$code = $_POST['BasicInfo']['smsCode'];
		
		
		if($info->tries >= 4)
		{  
			
			$admin =  User::model()->find("Fk_usertype=:Fk_usertype",array(':Fk_usertype'=>3));
			echo "Too many wrong inputs, please contact " . $admin->email . "\n";
			return;
		}
		
		if($code == NULL)
		{
			echo "Please enter a code";
			return;
		}
		
		if($code != $info->smsCode)
		{ 
			$info->tries++;
			$info->save(false);
			echo "Invalid code";
			return;
		}
		
		$info->validated = 1;
		$info->tries = 0;
		$info->smsCode = NULL;
		$info->save(false);
		
		echo "";
	}
	
	//sends a verification code to the user
	public function actionSendcode()
	{
		$username = Yii::app()->user->name;
		$model = User::model()->find("username=:username",array(':username'=>$username));
		$info = BasicInfo::model()->find("userid=:userid",array('userid'=>$model->id));
		
		if(!$info->phone)
		{
			echo "There is no phone number on record, please add a phone number to your profile";
			return;
		}
		
		
		
		$code = rand(1000,9999);
		
		$info->smsCode = $code;
		$info->save(false);
		
		$msg = "Your activation code for the Virtual Job Fair website is ". $code;
		
		spl_autoload_unregister(array('YiiBase','autoload'));
		require('Services/Twilio.php');
		$sid = "AC1a9ec3e5aaf3135a5e4893c095be8430";
		$token = "15871d8b55c402145f12c77dd7525644";
		$client = new Services_Twilio($sid, $token);
			
		$client->account->messages->sendMessage("+17868375870", "+1".$info->phone  ,$msg );
		spl_autoload_register(array('YiiBase','autoload'));
		
		echo "";
		
		
	}
	
	public function actionChangeSMSpref()
	
	{
		$username = Yii::app()->user->name;
		$model = User::model()->find("username=:username",array(':username'=>$username));
		$info = BasicInfo::model()->find("userid=:userid",array('userid'=>$model->id));
		$SMS = new SMS();
		
		
			$selection = $_POST['BasicInfo']['allowSMS'];
			$info->allowSMS = $selection;
			$info->save(false);  
			
			
			if(User::isCUrrentUserStudent() && $info->validated == 1)
			{
					
				$this->render('verified',array(
						'info'=>$info
				));
				return;
			}
			
		
			$this->render('sendsms',array(
					'info'=> $info,'SMS'=>$SMS
			));
			//$this->actionSendsms();
	}
	
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new SMS;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['SMS']))
		{
			$model->attributes=$_POST['SMS'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['SMS']))
		{
			$model->attributes=$_POST['SMS'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('SMS');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new SMS('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['SMS']))
			$model->attributes=$_GET['SMS'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return SMS the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=SMS::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param SMS $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='sms-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
