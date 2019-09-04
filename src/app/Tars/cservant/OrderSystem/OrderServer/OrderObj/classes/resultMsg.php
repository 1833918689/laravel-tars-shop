<?php

namespace App\Tars\cservant\OrderSystem\OrderServer\OrderObj\classes;

class resultMsg extends \TARS_Struct {
	const CODE = 0;
	const ERROR = 1;


	public $code= 200; 
	public $error; 


	protected static $_fields = array(
		self::CODE => array(
			'name'=>'code',
			'required'=>true,
			'type'=>\TARS::STRING,
			),
		self::ERROR => array(
			'name'=>'error',
			'required'=>false,
			'type'=>\TARS::STRING,
			),
	);

	public function __construct() {
		parent::__construct('OrderSystem_OrderServer_OrderObj_resultMsg', self::$_fields);
	}
}
