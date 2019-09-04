<?php

namespace App\Tars\cservant\PaySystem\PayService\PayObj\classes;

class InUpdateMechat extends \TARS_Struct {
	const ERR = 0;


	public $err; 


	protected static $_fields = array(
		self::ERR => array(
			'name'=>'err',
			'required'=>true,
			'type'=>\TARS::INT32,
			),
	);

	public function __construct() {
		parent::__construct('PaySystem_PayService_PayObj_InUpdateMechat', self::$_fields);
	}
}
