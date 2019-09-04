<?php

namespace App\Tars\cservant\BB\UserService\User\classes;

class TimeQueryParam extends \TARS_Struct {
	const STARTTIME = 0;
	const ENDTIME = 1;


	public $startTime; 
	public $endTime; 


	protected static $_fields = array(
		self::STARTTIME => array(
			'name'=>'startTime',
			'required'=>false,
			'type'=>\TARS::STRING,
			),
		self::ENDTIME => array(
			'name'=>'endTime',
			'required'=>false,
			'type'=>\TARS::STRING,
			),
	);

	public function __construct() {
		parent::__construct('BB_UserService_User_TimeQueryParam', self::$_fields);
	}
}
