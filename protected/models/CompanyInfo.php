<?php

/**
 * This is the model class for table "company_info".
 *
 * The followings are the available columns in table 'company_info':
 * @property integer $FK_userid
 * @property string $name
 * @property string $street
 * @property string $street2
 * @property string $city
 * @property string $state
 * @property string $zipcode
 * @property string $website
 * @property string $description
 *
 * The followings are the available model relations:
 * @property User $fKUser
 */
class CompanyInfo extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return CompanyInfo the static model class
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
		return 'company_info';
	}
	
	public static function getNames()
	{
		$companies = CompanyInfo::model()->findAll();
		$names = array();
		
		if ($companies != null)
			foreach ($companies as $aCompany)
				$names[] = $aCompany->name;
		
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
			array('name, street, street2, city, state, zipcode, website', 'length', 'max'=>45),
			array('description', 'safe'),
			array('name, street, city, state, zipcode, description','required'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('FK_userid, name, street, street2, city, state, zipcode, website, description', 'safe', 'on'=>'search'),
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
			'FK_userid' => 'Fk Userid',
			'name' => 'Company Name',
			'street' => 'Street',
			'street2' => 'Street2',
			'city' => 'City',
			'state' => 'State',
			'zipcode' => 'Zipcode',
			'website' => 'Company Website',
			'description' => 'Company Description',
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

		$criteria->compare('FK_userid',$this->FK_userid);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('street',$this->street,true);
		$criteria->compare('street2',$this->street2,true);
		$criteria->compare('city',$this->city,true);
		$criteria->compare('state',$this->state,true);
		$criteria->compare('zipcode',$this->zipcode,true);
		$criteria->compare('website',$this->website,true);
		$criteria->compare('description',$this->description,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public static function getCompanyNames() {

		$companies = CompanyInfo::model()->findAll();
		$names = array();
	
		if ($companies != null)
			foreach ($companies as $company)
				$names[] = $company->name;
	
		return $names;

	}

    public static function getCompanyNamesUser($fKPoster) {

        $names = CompanyInfo::model()->findBySql("SELECT company_info.name FROM company_info WHERE FK_userid=:FK_poster", array(":FK_poster"=>$fKPoster));

        return $names->name;

    }
}