<?php

/**
 * This is the model class for table "school".
 *
 * The followings are the available columns in table 'school':
 * @property integer $id
 * @property string $name
 * @property string $email_string
 *
 * The followings are the available model relations:
 * @property Education[] $educations
 */
class School extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return School the static model class
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
		return 'school';
	}
	
	public function getSchoolId($schoolName)
	{
		$schoolID = School::model()->find("name=:schoolName",array(':schoolName'=>$schoolName));
		if($schoolID ==null){
			return "null";
		}
		
		return $schoolID->id;
	}	
	/**
	 * Returns array of all schools
	 */
	public static function getAllSchools()
	{
		$schools = School::model()->findAll();
		$schoolNames = array();
		
		foreach($schools as $school)
			$schoolNames[] = $school->name;
			
		return $schoolNames;	
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name', 'required'),
			array('name', 'length', 'max'=>100),
			array('email_string', 'length', 'max'=>45),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, email_string', 'safe', 'on'=>'search'),
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
			'educations' => array(self::HAS_MANY, 'Education', 'FK_school_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'email_string' => 'Email String',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('email_string',$this->email_string,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}