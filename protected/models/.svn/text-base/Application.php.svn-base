<?php

/**
 * This is the model class for table "application".
 *
 * The followings are the available columns in table 'application':
 * @property integer $jobid
 * @property integer $userid
 * @property string $application_date
 */
class Application extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Application the static model class
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
		return 'application';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('jobid, userid, application_date', 'required'),
			array('jobid, userid', 'numerical', 'integerOnly'=>true),
			array('application_date', 'length', 'max'=>45),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('jobid, userid, application_date', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'jobid' => 'Jobid',
			'userid' => 'Userid',
			'application_date' => 'Application Date',
			'coverletter' => 'Cover Letter',
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

		$criteria->compare('jobid',$this->jobid);
		$criteria->compare('userid',$this->userid);
		$criteria->compare('application_date',$this->application_date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	/*
	 * Check if the current user has applied to a job yet
	 */
	public static function hasApplied($jobid){
		$user = User::getCurrentUser();
		$application = Application::model()->find("jobid=:jobid AND userid=:userid", array(":jobid"=>$jobid, ":userid"=>$user->id));
		return ($application != null);
	}
}