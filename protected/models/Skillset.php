<?php

/**
 * This is the model class for table "skillset".
 *
 * The followings are the available columns in table 'skillset':
 * @property integer $id
 * @property string $name
 *
 * The followings are the available model relations:
 * @property JobSkillMap[] $jobSkillMaps
 * @property SkillBrowse[] $skillBrowses
 * @property StudentSkillMap[] $studentSkillMaps
 */
class Skillset extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Skillset the static model class
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
		return 'skillset';
	}
	
	public static function getNames()
	{
		$skills = Skillset::model()->findAll();
		$names = array();
	
		if ($skills != null)
			foreach ($skills as $aSkill)
			$names[] = $aSkill->name;
	
		return $names;
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
			array('name', 'length', 'max'=>45),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('name', 'safe', 'on'=>'search'),
            array('name', 'uniqueSkill')
		);
	}

    public function uniqueSkill($attribute,$params)
    {
        if ($this->isNewRecord)
        {
            $dup_skill = Skillset::model()->find("name=:name", array(':name' => $this->name));
        }

        if (isset($dup_skill))
            $this->addError($attribute, 'Skill name already exists, please select a different one!');
    }

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'jobSkillMaps' => array(self::HAS_MANY, 'JobSkillMap', 'skillid'),
			'skillBrowses' => array(self::HAS_MANY, 'SkillBrowse', 'skill_id'),
			'studentSkillMaps' => array(self::HAS_MANY, 'StudentSkillMap', 'skillid'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			//'id' => 'ID',
			'name' => 'Name',
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

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
            'pagination'=>array(
                'pageSize'=>15,
            ),
		));
	}
}