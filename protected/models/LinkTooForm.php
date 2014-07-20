<?php

class LinkTooForm extends CFormModel
{
    public $firstname;
    public $firstnamevar;
    public $profilePic;
    public $phone;
    public $phonevar;
    public $toPost;
    public $lastnamevar;
    public $lastname;
    public $profilePicVar;
    public $city;
    public $cityvar;
    public $email;
    public $emailvar;
    public $state;
    public $statevar;
    public $about_me;
    public $about_me_var;
    public $password;
    public $allowExpired;
    public $next;

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules()
    {
        return array(
            array('firstname', 'length', 'max'=>255),
            array('firstnamevar', 'length', 'max'=>255),
            array('toPost', 'length', 'max'=>255),
            array('password', 'length', 'max'=>255),
            array('lastnamevar', 'length', 'max'=>255),
            array('email', 'length', 'max'=>255),
            array('emailvar', 'length', 'max'=>255),
            array('state', 'length', 'max'=>255),
            array('statevar', 'length', 'max'=>255),
            array('about_me', 'length', 'max'=>255),
            array('about_me_var', 'length', 'max'=>255),
            array('profilePic', 'length', 'max'=>255),
            array('phone', 'length', 'max'=>255),
            array('phonevar', 'length', 'max'=>255),
            array('lastname', 'length', 'max'=>255),
            array('profilePicVar', 'length', 'max'=>255),
            array('city', 'length', 'max'=>255),
            array('cityvar', 'length', 'max'=>255),
            array('allowExpired', 'boolean'),
            array('next', 'length', 'max'=>255),

        );
    }

    public function attributeLabels()
    {
        return array(
            'firstname'=> '',
            'firstnamevar'=>'',
            'toPost'=>'',
            'password'=>'',
            'lastnamevar'=>'',
            'email'=>'',
            'emailvar'=>'',
            'state'=>'',
            'statevar'=>'',
            'about_me'=>'',
            'about_me_var'=>'',
            'profilePic'=>'',
            'phone'=>'',
            'phonevar'=>'',
            'lastname'=>'',
            'profilePicVar'=>'',
            'city'=>'',
            'cityvar'=>'',
            'next'=>'',
            'allowExpired'=>'Allow Expired Jobs',
        );
    }

//    function validDateRange()
//    {
//        $sdate = strtotime($this->dateFrom);
//        $edate = strtotime($this->dateTo);
//
//        if ($edate < $sdate)
//            $this->addError('dateTo', 'End date cannot be smaller than start date');
//        else if ($sdate > $edate)
//            $this->addError('dateFrom', 'Start date cannot be greater than end date');
//    }
}