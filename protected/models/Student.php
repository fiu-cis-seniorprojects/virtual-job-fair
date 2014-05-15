<?php

class Student extends User
{
			
/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return User the static model class
	 */
	//public $FK_usertype = 1;
	public static function model($className=__CLASS__)
	{
		return parent::model("User");
	}	
}
?>