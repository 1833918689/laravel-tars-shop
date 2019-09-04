<?php

namespace App\Tars\cservant\IntegralSystem\IntegralServer\IntegralObj\classes;

class InUpdateUserAccount extends \TARS_Struct {
	const UUID = 0;
	const POINTS = 1;
	const TYPE = 2;
	const MARK = 3;
	const ENV_DOMAIN_ID = 4;


	public $uuid; 
	public $points; 
	public $type; 
	public $mark; 
	public $env_domain_id; 


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
		self::TYPE => array(
			'name'=>'type',
			'required'=>true,
			'type'=>\TARS::INT32,
			),
		self::MARK => array(
			'name'=>'mark',
			'required'=>true,
			'type'=>\TARS::INT32,
			),
		self::ENV_DOMAIN_ID => array(
			'name'=>'env_domain_id',
			'required'=>true,
			'type'=>\TARS::INT32,
			),
	);

	public function __construct() {
		parent::__construct('IntegralSystem_IntegralServer_IntegralObj_InUpdateUserAccount', self::$_fields);
	}
}
