<?php

/**
 * This is the model class for table "education".
 *
 * The followings are the available columns in table 'education':
 * @property integer $id
 * @property string $degree
 * @property string $major
 * @property string $graduation_date
 * @property integer $FK_school_id
 * @property integer $FK_user_id
 * @property double $gpa
 * @property string $additional_info
 *
 * The followings are the available model relations:
 * @property User $fKUser
 * @property School $fKSchool
 */
class Education extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Education the static model class
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
		return 'education';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('degree, major, graduation_date', 'required'),
			array('FK_school_id, FK_user_id', 'numerical', 'integerOnly'=>true),
			array('gpa', 'numerical'),
			array('degree, major', 'length', 'max'=>45),
			array('additional_info', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, degree, major, graduation_date, FK_school_id, FK_user_id, gpa, additional_info', 'safe', 'on'=>'search'),
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
			'fKUser' => array(self::BELONGS_TO, 'User', 'FK_user_id'),
			'fKSchool' => array(self::BELONGS_TO, 'School', 'FK_school_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'degree' => 'Degree',
			'major' => 'Major',
			'graduation_date' => 'Graduation Date',
			'FK_school_id' => 'Fk School',
			'FK_user_id' => 'Fk User',
			'gpa' => 'Gpa',
			'additional_info' => 'Additional Info',
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
		$criteria->compare('degree',$this->degree,true);
		$criteria->compare('major',$this->major,true);
		$criteria->compare('graduation_date',$this->graduation_date,true);
		$criteria->compare('FK_school_id',$this->FK_school_id);
		$criteria->compare('FK_user_id',$this->FK_user_id);
		$criteria->compare('gpa',$this->gpa);
		$criteria->compare('additional_info',$this->additional_info,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}