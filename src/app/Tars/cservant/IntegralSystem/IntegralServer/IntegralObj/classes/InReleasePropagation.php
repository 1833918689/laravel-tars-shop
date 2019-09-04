<?php

namespace App\Tars\cservant\IntegralSystem\IntegralServer\IntegralObj\classes;

class InReleasePropagation extends \TARS_Struct {
	const SHOP_ID = 0;
	const POINTS = 1;
	const END_TIME = 2;
	const ENV_DOMAIN_ID = 3;


	public $shop_id; 
	public $points; 
	public $end_time; 
	public $env_domain_id; 


	protected static $_fields = array(
		self::SHOP_ID => array(
			'name'=>'shop_id',
			'required'=>true,
			'type'=>\TARS::STRING,
			),
		self::POINTS => array(
			'name'=>'points',
			'required'=>true,
			'type'=>\TARS::FLOAT,
			),
		self::END_TIME => array(
			'name'=>'end_time',
			'required'=>true,
			'type'=>\TARS::STRING,
			),
		self::ENV_DOMAIN_ID => array(
			'name'=>'env_domain_id',
			'required'=>true,
			'type'=>\TARS::INT32,
			),
	);

	public function __construct() {
		parent::__construct('IntegralSystem_IntegralServer_IntegralObj_InReleasePropagation', self::$_fields);
	}
}
