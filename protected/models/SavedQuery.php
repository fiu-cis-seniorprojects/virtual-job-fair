<?php

/**
 * This is the model class for table "saved_queries".
 *
 * The followings are the available columns in table 'experience':
 * @property integer $id
 * @property integer $FK_userid
 * @property string $query_tag
 * @property string $query
 * @property string $location
 * @property integer $active
 *
 * The followings are the available model relations:
 * @property User $fKUser
 */
class SavedQuery extends CActiveRecord
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
        return 'saved_queries';
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
            array('id, FK_userid', 'active', 'numerical', 'integerOnly'=>true),
            array('query_tag, query, location', 'length', 'max'=>45),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, FK_userid, query_tag, query, location', 'active', 'safe', 'on'=>'search'),
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
            'query_tag' => 'Query Name',
            'query' => 'Query',
            'location' => 'Location',
            'active' => 'Active'
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
        $criteria->compare('query_tag',$this->query_tag,true);
        $criteria->compare('query',$this->query,true);
        $criteria->compare('location',$this->location,true);
        $criteria->compare('active', $this->active, true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }
}