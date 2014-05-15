<?php

/**
 * This is the model class for table "SMS".
 *
 * The followings are the available columns in table 'SMS':
 * @property integer $id
 * @property integer $receiver_id
 * @property integer $sender_id
 * @property string $date
 * @property string $subject
 * @property string $Message
 *
 * The followings are the available model relations:
 * @property User $receiver
 * @property User $sender
 */
class SMS extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return SMS the static model class
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
		return 'SMS';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, receiver_id, sender_id, date, subject, Message', 'required'),
			array('id, receiver_id, sender_id', 'numerical', 'integerOnly'=>true),
			array('subject', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, receiver_id, sender_id, date, subject, Message', 'safe', 'on'=>'search'),
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
			'receiver' => array(self::BELONGS_TO, 'User', 'receiver_id'),
			'sender' => array(self::BELONGS_TO, 'User', 'sender_id'),
		);
	}

	
	
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'receiver_id' => 'Receiver screen name',
			'sender_id' => 'Sender',
			'date' => 'Date',
			'subject' => 'Subject',
			'Message' => 'Message',
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
		$criteria->compare('receiver_id',$this->receiver_id);
		$criteria->compare('sender_id',$this->sender_id);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('subject',$this->subject,true);
		$criteria->compare('Message',$this->Message,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}