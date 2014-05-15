<?php

/**
 * This is the model class for table "user_document".
 *
 * The followings are the available columns in table 'user_document':
 * @property integer $id
 * @property integer $active_status
 * @property string $document_id
 * @property integer $local_user_id
 * @property integer $remote_user_id
 * @property integer $owner_id
 * @property string $document_path
 * @property string $document_name
 * @property string $owner_url
 * @property string $viewer_url
 */
class UserDocument extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return UserDocument the static model class
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
		return 'user_document';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('document_id, local_user_id, remote_user_id, owner_id, document_path, document_name, owner_url, viewer_url', 'required'),
			array('active_status, local_user_id, remote_user_id, owner_id', 'numerical', 'integerOnly'=>true),
			array('document_id, document_path, document_name, owner_url, viewer_url', 'length', 'max'=>256),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, active_status, document_id, local_user_id, remote_user_id, owner_id, document_path, document_name, owner_url, viewer_url', 'safe', 'on'=>'search'),
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
			'id' => 'ID',
			'active_status' => 'Active Status',
			'document_id' => 'Document',
			'local_user_id' => 'Local User',
			'remote_user_id' => 'Remote User',
			'owner_id' => 'Owner',
			'document_path' => 'Document Path',
			'document_name' => 'Document Name',
			'owner_url' => 'Owner Url',
			'viewer_url' => 'Viewer Url',
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
		$criteria->compare('active_status',$this->active_status);
		$criteria->compare('document_id',$this->document_id,true);
		$criteria->compare('local_user_id',$this->local_user_id);
		$criteria->compare('remote_user_id',$this->remote_user_id);
		$criteria->compare('owner_id',$this->owner_id);
		$criteria->compare('document_path',$this->document_path,true);
		$criteria->compare('document_name',$this->document_name,true);
		$criteria->compare('owner_url',$this->owner_url,true);
		$criteria->compare('viewer_url',$this->viewer_url,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}