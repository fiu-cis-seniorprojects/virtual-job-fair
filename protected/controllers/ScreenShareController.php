<?php

class ScreenShareController extends Controller
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
				'actions'=>array('create','update','view','GetScreenLeap','GetviewerUrl','Setstop'),
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
	public function actionView()
	{   
		$username = Yii::app()->user->name;
		$model = User::model()->find("username=:username",array(':username'=>$username));
		
		if($model->FK_usertype == 1)  // student type
		{
		$id = VideoInterview::model()->findByAttributes(array('FK_student'=>$model->id));
		}
		
		else{                         // employer
			$id = VideoInterview::model()->findByAttributes(array('FK_employer'=>$model->id));
		}
		
		
		
		
		
		//Yii::log("the user name is", CLogger::LEVEL_ERROR, 'application.controller.Prof');
		
		
		
		$this->render('view',array(
			'model'=>$id
		));
		
	}
		
		// ajax call returns screenleap java applet data 
	public function actionGetScreenLeap() {
		$username = yii::app ()->user->name;
		$model = User::model ()->find ( "username=:username", array (
				':username' => $username 
		) );
		$session = $_GET ["session"]; //Video Interview session id
		
		$SsModel = ScreenShare::model ()->find ( "Session_Key=:session", array (
				':session' => $session 
		) );
		
		if ($SsModel->ScreenShareView) { // check if stream is active
			$matches = array ();
			$code = preg_match ( "/viewer\/(.*)\?/", $SsModel->ScreenShareView, $matches ); // regex to extract screenleap code
			
			$ch = curl_init ( "https://api.screenleap.com/v2/screen-shares/" . $matches [1] );
			curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
			curl_setopt ( $ch, CURLOPT_HTTPHEADER, array (
					'accountid:jfern096',
					'authtoken:qsvPoUtCcc' 
			) );
			
			$data = curl_exec ( $ch );
			curl_close ( $ch );
			$json = json_decode ( $data, true );
			
			if (! isset ( $json ["endTime"] ) && $SsModel->sharingscreen == 1 && $json ["shareState"] != "NOT_STARTED") {
				
				$arr = array (
						'a' => "There is an active screenShare at the moment, please wait until it has ended before 
						attempting to share a screen" 
				);
				echo json_encode ( $arr );
				return;
			}
		}
		
		$url = 'https://api.screenleap.com/v2/screen-shares';
		$curl_handle = curl_init ( $url );
		curl_setopt ( $curl_handle, CURLOPT_POST, 1 );
		curl_setopt ( $curl_handle, CURLOPT_HTTPHEADER, array (
				'authtoken:qsvPoUtCcc' 
		) );
		curl_setopt ( $curl_handle, CURLOPT_POSTFIELDS, 'accountid=jfern096' );
		curl_setopt ( $curl_handle, CURLOPT_RETURNTRANSFER, true ); // return as a string instead of outputting directly
		$data = curl_exec ( $curl_handle );
		curl_close ( $curl_handle );
		$json = json_decode ( $data, true );
		
		$SsModel->ScreenShareView = $json ["viewerUrl"];
		$SsModel->sharingscreen = 1;
		$SsModel->save ( false );
		
		echo $data;
	}
	 
	 
	 
	 public function actionGetviewerUrl(){
	 	$username = yii::app()->user->name;
	 	$model = User::model()->find("username=:username",array(':username'=>$username));
	 	$session = $_GET["session"];
	 	
	 
	 	
	  	$SsModel = ScreenShare::model()->find("Session_Key=:session",array(':session'=>$session));
	 	
	  	if($SsModel->ScreenShareView)
	  	{
	  	
	 		echo $SsModel->ScreenShareView;
	  	}
	  	else{
	  		echo $this->createUrl('screenShare/index');
	  	}
	 }
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	 
  		/*set screenshare as inactive on end action*/
	 public function actionSetstop()
	 {
	 	$username = yii::app()->user->name;
	 	$model = User::model()->find("username=:username",array(':username'=>$username));
	 	$session = $_GET["session"];
	 	$SsModel = ScreenShare::model()->find("Session_Key=:session",array(':session'=>$session));
	 	
	 	/*only students or employers involved in interview may change this field*/
	 	if($model->id == $SsModel->FK_employer ||$model->id == $SsModel->FK_student )
	 	{
	 		$SsModel->sharingscreen = 0;
	 		$SsModel->save(false);
	 	
	
	 	}
	 	
	 }
	 
	 
	 
	 
	 
	 
	 
	public function actionCreate()
	{
		$model=new ScreenShare;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['ScreenShare']))
		{
			$model->attributes=$_POST['ScreenShare'];
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

		if(isset($_POST['ScreenShare']))
		{
			$model->attributes=$_POST['ScreenShare'];
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
		
		$this->renderPartial('index',array(
			
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new ScreenShare('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['ScreenShare']))
			$model->attributes=$_GET['ScreenShare'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return ScreenShare the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=ScreenShare::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param ScreenShare $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='screen-share-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
