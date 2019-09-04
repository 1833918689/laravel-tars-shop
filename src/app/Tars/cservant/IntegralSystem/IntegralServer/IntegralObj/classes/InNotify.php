<?php

namespace App\Tars\cservant\IntegralSystem\IntegralServer\IntegralObj\classes;

class InNotify extends \TARS_Struct {
	const CODE = 0;
	const PAID = 1;


	public $code; 
	public $paid; 


	protected static $_fields = array(
		self::CODE => array(
			'name'=>'code',
			'required'=>true,
			'type'=>\TARS::STRING,
			),
		self::PAID => array(
			'name'=>'paid',
			'required'=>true,
			'type'=>\TARS::STRING,
			),
	);

	public function __construct() {
		parent::__construct('IntegralSystem_IntegralServer_IntegralObj_InNotify', self::$_fields);
	}
}
