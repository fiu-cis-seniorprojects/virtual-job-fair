<?php

class ApiConfigForm extends CFormModel
{
    public $dateTo;
    public $dateFrom;
    public $allowExpired;

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules()
    {
        return array(
            array('dateTo, dateFrom', 'required'),
            array('allowExpired', 'boolean'),
            array('dateTo', 'validDateRange'),
            array('dateFrom', 'validDateRange'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'dateFrom'=>'Start Date:',
            'dateTo'=>'End Date:',
            'allowExpired'=>'Allow Expired Jobs',
        );
    }

    function validDateRange()
    {
        $sdate = strtotime($this->dateFrom);
        $edate = strtotime($this->dateTo);

        if ($edate < $sdate)
            $this->addError('dateTo', 'End date cannot be smaller than start date');
        else if ($sdate > $edate)
            $this->addError('dateFrom', 'Start date cannot be greater than end date');
    }
}