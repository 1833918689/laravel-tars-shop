<?php

namespace App\Tars\cservant\IntegralSystem\IntegralServer\IntegralObj\classes;

class InTransaction extends \TARS_Struct {
	const UUID = 0;
	const SHOP_ID = 1;
	const POINTS = 2;
	const TYPE_MARK = 3;


	public $uuid; 
	public $shop_id; 
	public $points; 
	public $type_mark; 


	protected static $_fields = array(
		self::UUID => array(
			'name'=>'uuid',
			'required'=>true,
			'type'=>\TARS::STRING,
			),
		self::SHOP_ID => array(
			'name'=>'shop_id',
			'required'=>true,
			'type'=>\TARS::INT32,
			),
		self::POINTS => array(
			'name'=>'points',
			'required'=>true,
			'type'=>\TARS::FLOAT,
			),
		self::TYPE_MARK => array(
			'name'=>'type_mark',
			'required'=>true,
			'type'=>\TARS::STRING,
			),
	);

	public function __construct() {
		parent::__construct('IntegralSystem_IntegralServer_IntegralObj_InTransaction', self::$_fields);
	}
}
