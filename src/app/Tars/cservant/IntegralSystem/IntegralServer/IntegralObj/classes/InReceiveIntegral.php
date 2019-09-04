<?php

namespace App\Tars\cservant\IntegralSystem\IntegralServer\IntegralObj\classes;

class InReceiveIntegral extends \TARS_Struct {
	const UUID = 0;
	const POINTS = 1;
	const WITHHOLDING_CODE = 2;


	public $uuid; 
	public $points; 
	public $withholding_code; 


	protected static $_fields = array(
		self::UUID => array(
			'name'=>'uuid',
			'required'=>true,
			'type'=>\TARS::STRING,
			),
		self::POINTS => array(
			'name'=>'points',
			'required'=>true,
			'type'=>\TARS::FLOAT,
			),
		self::WITHHOLDING_CODE => array(
			'name'=>'withholding_code',
			'required'=>true,
			'type'=>\TARS::STRING,
			),
	);

	public function __construct() {
		parent::__construct('IntegralSystem_IntegralServer_IntegralObj_InReceiveIntegral', self::$_fields);
	}
}
