<?php

/**
 * This is the model class for table "experience".
 *
 * The followings are the available columns in table 'experience':
 * @property integer $id
 * @property integer $FK_userid
 * @property string $company_name
 * @property string $job_title
 * @property string $job_description
 * @property string $startdate
 * @property string $enddate
 * @property string $city
 * @property string $state
 *
 * The followings are the available model relations:
 * @property User $fKUser
 */
class Experience extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Experience the static model class
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
		return 'experience';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id', 'required'),
			array('id, FK_userid', 'numerical', 'integerOnly'=>true),
			array('company_name, job_title, city, state', 'length', 'max'=>45),
			array('job_description, startdate, enddate', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, FK_userid, company_name, job_title, job_description, startdate, enddate, city, state', 'safe', 'on'=>'search'),
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
			'fKUser' => array(self::BELONGS_TO, 'User', 'FK_userid'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'FK_userid' => 'Fk Userid',
			'company_name' => 'Company Name',
			'job_title' => 'Job Title',
			'job_description' => 'Job Description',
			'startdate' => 'Startdate',
			'enddate' => 'Enddate',
			'city' => 'City',
			'state' => 'State',
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
		$criteria->compare('FK_userid',$this->FK_userid);
		$criteria->compare('company_name',$this->company_name,true);
		$criteria->compare('job_title',$this->job_title,true);
		$criteria->compare('job_description',$this->job_description,true);
		$criteria->compare('startdate',$this->startdate,true);
		$criteria->compare('enddate',$this->enddate,true);
		$criteria->compare('city',$this->city,true);
		$criteria->compare('state',$this->state,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}