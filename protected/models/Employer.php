<?php


class Employer extends User
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Employer the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}	
}