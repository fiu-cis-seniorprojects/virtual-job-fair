<?php

/**
 * This is the model class for table "handshake".
 *
 * The followings are the available columns in table 'handshake':
 * @property integer $id
 * @property integer $jobid
 * @property integer $employerid
 * @property integer $studentid
 *
 * The followings are the available model relations:
 * @property User $employer
 * @property User $student
 * @property Job $job
 */
class Handshake extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Handshake the static model class
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
		return 'handshake';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('employerid, studentid', 'required'),
			array('jobid, employerid, studentid', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, jobid, employerid, studentid', 'safe', 'on'=>'search'),
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
			'employer' => array(self::BELONGS_TO, 'User', 'employerid'),
			'student' => array(self::BELONGS_TO, 'User', 'studentid'),
			'job' => array(self::BELONGS_TO, 'Job', 'jobid'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'jobid' => 'Jobid',
			'employerid' => 'Employerid',
			'studentid' => 'Studentid',
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
		$criteria->compare('jobid',$this->jobid);
		$criteria->compare('employerid',$this->employerid);
		$criteria->compare('studentid',$this->studentid);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}