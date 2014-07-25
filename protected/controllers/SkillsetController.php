<?php

class SkillsetController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	//public $layout='//layouts/column2';

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
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('index', 'admin','create','update', 'delete', 'consolidate'),
				'users'=>array('admin','administrator'),
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
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Skillset;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Skillset']))
		{
			$model->attributes=$_POST['Skillset'];
            // dont allow duplicate skill names
//            $posting_user = Skillset::model()->find("name=:name", array(':name' => $model->attributes['name']));
//            if (isset($posting_user))
//            {
//               // array_push($model->errors, 'Duplicate skills are not allowed!');
//            }
//            else
//            {
                if ($model->save())
                    $this->redirect(array('admin'));
//            }
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

		if(isset($_POST['Skillset']))
		{
			$model->attributes=$_POST['Skillset'];
			if($model->save())
				$this->redirect(array('admin','id'=>$model->id));
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
		$this->actionAdmin();
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Skillset('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Skillset']))
			$model->attributes=$_GET['Skillset'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

    public function actionConsolidate()
    {
        $model=new Skillset('search');
        $model->unsetAttributes();  // clear any default values
        $error = '';
        if(isset($_GET['Skillset']))
            $model->attributes=$_GET['Skillset'];

        if (isset($_POST['skill_one']) && isset($_POST['skill_two']))
        {
            // make sure skill one exists on database
            $skill_one = Skillset::model()->find("name=:name", array(':name' => $_POST['skill_one']));
            if (!isset($skill_one))
            {
                $error = "Skill A not found!";
            }
            else
            {
                // make sure skill two exists on database
                $skill_two = Skillset::model()->find("name=:name", array(':name' => $_POST['skill_two']));
                if (!isset($skill_two))
                {
                    $error = "Skill B not found!";
                }
                else
                {
                    // make sure both skills are not the same one
                    if (strcmp($skill_one->name, $skill_two->name) != 0)
                    {
                        // merge skill one to skill two (skill two remains)
                        $jobskill_mappings = JobSkillMap::model()->findAll("skillid=:skillid", array(':skillid' => $skill_one->id));
                        foreach ($jobskill_mappings as $jobskill_mapping)
                        {
                            $jobskill_mapping->skillid = $skill_two->id;
                            $jobskill_mapping->save(false);
                        }

                        $studentskill_mappings = StudentSkillMap::model()->findAll("skillid=:skillid", array(':skillid' => $skill_one->id));
                        foreach ($studentskill_mappings as $studentskill_mapping)
                        {
                            $studentskill_mapping->skillid = $skill_two->id;
                            $studentskill_mapping->save(false);
                        }

                        $skill_one->delete();

                        $this->redirect(array('admin'));
                    }
                    else
                    {
                        $error = "Skill A and Skill B cannot be the same!";
                    }
                }
            }
        }

        $this->render('consolidate',array(
            'model'=>$model,
            'error'=>$error,
            'skill1'=>(isset($_POST['skill_one']) ? $_POST['skill_one']  : ''),
            'skill2'=>(isset($_POST['skill_two']) ? $_POST['skill_two']  : ''),
        ));
    }

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Skillset the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Skillset::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Skillset $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='skillset-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
