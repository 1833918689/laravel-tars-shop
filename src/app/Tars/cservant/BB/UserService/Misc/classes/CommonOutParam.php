<?php

namespace App\Tars\cservant\BB\UserService\Misc\classes;

class CommonOutParam extends \TARS_Struct {
	const CODE = 0;
	const MESSAGE = 1;


	public $code= 0; 
	public $message= "success"; 


	protected static $_fields = array(
		self::CODE => array(
			'name'=>'code',
			'required'=>true,
			'type'=>\TARS::INT32,
			),
		self::MESSAGE => array(
			'name'=>'message',
			'required'=>true,
			'type'=>\TARS::STRING,
			),
	);

	public function __construct() {
		parent::__construct('BB_UserService_Misc_CommonOutParam', self::$_fields);
	}
}
