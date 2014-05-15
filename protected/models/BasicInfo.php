<?php

/**
 * This is the model class for table "basic_info".
 *
 * The followings are the available columns in table 'basic_info':
 * @property integer $userid
 * @property string $phone
 * @property string $city
 * @property string $state
 * @property string $allowSMS
 * The followings are the available model relations:
 * @property User $user
 */
class BasicInfo extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return BasicInfo the static model class
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
		return 'basic_info';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('userid, about_me, hide_phone, allowSMS','required'),
			array('userid', 'numerical', 'integerOnly'=>true),
			array('phone', 'length', 'max'=>15),
			array('city, state', 'length', 'max'=>45),
		
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('userid, phone, city, state', 'safe', 'on'=>'search'),
		   
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
			'user' => array(self::BELONGS_TO, 'User', 'userid'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'userid' => 'Userid',
			'phone' => 'Phone',
			'city' => 'City',
			'state' => 'State',
			'about_me' => 'About Me',
			'hide_phone' => 'Hide phone from students?',
			'allowSMS' => 'Would you like to receive SMS? '
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

		$criteria->compare('userid',$this->userid);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('city',$this->city,true);
		$criteria->compare('state',$this->state,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}