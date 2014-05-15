<?php

/**
 * This is the model class for table "whiteboard_sessions".
 *
 * The followings are the available columns in table 'whiteboard_sessions':
 * @property integer $whiteboard_id
 * @property string $user1
 * @property string $user2
 * @property integer $interview_id
 */
class WhiteboardSessions extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return WhiteboardSessions the static model class
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
		return 'whiteboard_sessions';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('whiteboard_id, interview_id', 'numerical', 'integerOnly'=>true),
			array('user1, user2', 'length', 'max'=>15),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('whiteboard_id, user1, user2, interview_id', 'safe', 'on'=>'search'),
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
			'whiteboard_id' => 'Whiteboard',
			'user1' => 'User1',
			'user2' => 'User2',
			'interview_id' => 'Interview',
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

		$criteria->compare('whiteboard_id',$this->whiteboard_id);
		$criteria->compare('user1',$this->user1,true);
		$criteria->compare('user2',$this->user2,true);
		$criteria->compare('interview_id',$this->interview_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}