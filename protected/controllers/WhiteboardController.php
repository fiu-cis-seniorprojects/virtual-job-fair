<?php

class WhiteboardController extends Controller
{
        public function actionCreateDocument()
        {
                $this->render('createDocument');
        }

        public function actionDeleteDocument()
        {
                $this->render('deleteDocument');
        }

        public function actionExportDocument()
        {
                $this->render('exportDocument');
        }

        public function actionHome()
        {
                $this->render('home');
        }

        public function actionImportDocument()
        {
                $this->render('importDocument');
        }

        public function actionListDocument()
        {
                $this->render('listDocument');
        }

        public function actionOpenDocument()
        {
                $this->render('openDocument');
        }

        public function actionRenameDocument()
        {
                $this->render('renameDocument');
        }

		        public function actionSaveDocument()
        {
                $this->render('saveDocument');
        }

        public function actionShareDocument()
        {
                $this->render('shareDocument');
        }

        public function actionCheckWhiteboardExists()
        {
                $session = $_GET["session"];

                $query1 = "SELECT * FROM whiteboard_sessions WHERE interview_id='$session'";

                $command = Yii::app()->db->createCommand( $query1 );
                $list = $command->queryAll();
                $command = false;

				                if( !empty( $list ) )
                {
                        $board_id = $list[0]["whiteboard_id"];
                        echo $board_id;
                }
                else
                        echo "";
        }



       public function actionCreateWhiteboardSession()
        {


		$session = $_POST[ "session" ];
                $query = "SELECT * FROM whiteboard_sessions WHERE interview_id='$session'";

                $command = Yii::app()->db->createCommand( $query );

              $list = $command->queryAll();
              $command = false;


                if( empty( $list ) )
                {
			$user1 = $_POST["user1"];
			$user2 = $_POST["user2"];

			$otherQuery = "INSERT INTO whiteboard_sessions VALUES( '$user1', '$user2', '$session', 'none', DEFAULT );";

			$result = Yii::app()->db->createCommand( $otherQuery );
			$result2 = $result->execute();
			echo "new";
		}
		else 
 		   echo "old";
}



	 public function actionCheckDrawingExists(){
					
		$session = $_GET[ "session" ];

                $query = "SELECT * FROM whiteboard_sessions WHERE interview_id='$session'";
                $command = Yii::app()->db->createCommand( $query );

                $list = $command->queryAll();
                $command = false;


                if( empty( $list ) )
                {
                        echo "";
                        return;
                }

                 $image_name = $list[0]["image_name"];

                if( $image_name == "none" )
                {
                        echo "";
                }
                else
                {
                        echo $image_name;
                }

        }


        public function actionUploadImage()
        {
                $session =  $_POST[ "session" ];
                $image_name = $_POST[ "image_name" ];
                $image_name = $session . substr( $image_name, strrpos( $image_name, "." ) );

		$extension = substr( $image_name, strrpos( $image_name, "." ) + 1 );

		if( $extension == "jpg" || $extension == "jpeg" || $extension == "gif" || $extension == "pjpeg" || $extension == "x-png" || $extension == "png" )
		{
                $query = "UPDATE whiteboard_sessions SET image_name='$image_name' WHERE interview_id='$session'";
                $result = Yii::app()->db->createCommand( $query );
                $result2 = $result->execute();

                echo $image_name;
		}
		else
		{
			echo "";
		}
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

