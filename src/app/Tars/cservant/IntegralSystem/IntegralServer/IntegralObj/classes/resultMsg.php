<?php

namespace App\Tars\cservant\IntegralSystem\IntegralServer\IntegralObj\classes;

class resultMsg extends \TARS_Struct {
	const CODE = 0;
	const ERROR = 1;


	public $code=200; 
	public $error; 


	protected static $_fields = array(
		self::CODE => array(
			'name'=>'code',
			'required'=>true,
			'type'=>\TARS::INT32,
			),
		self::ERROR => array(
			'name'=>'error',
			'required'=>false,
			'type'=>\TARS::STRING,
			),
	);

	public function __construct() {
		parent::__construct('IntegralSystem_IntegralServer_IntegralObj_resultMsg', self::$_fields);
	}
}
