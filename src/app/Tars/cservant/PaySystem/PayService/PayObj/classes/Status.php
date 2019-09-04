<?php

namespace App\Tars\cservant\PaySystem\PayService\PayObj\classes;

class Status extends \TARS_Struct {
	const ERR = 0;
	const MSG = 1;


	public $err; 
	public $msg; 


	protected static $_fields = array(
		self::ERR => array(
			'name'=>'err',
			'required'=>true,
			'type'=>\TARS::INT32,
			),
		self::MSG => array(
			'name'=>'msg',
			'required'=>false,
			'type'=>\TARS::STRING,
			),
	);

	public function __construct() {
		parent::__construct('PaySystem_PayService_PayObj_Status', self::$_fields);
	}
}
