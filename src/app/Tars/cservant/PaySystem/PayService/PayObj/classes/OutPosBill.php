<?php

namespace App\Tars\cservant\PaySystem\PayService\PayObj\classes;

class OutPosBill extends \TARS_Struct {
	const TOTAL = 0;
	const ITEMS = 1;


	public $total; 
	public $items; 


	protected static $_fields = array(
		self::TOTAL => array(
			'name'=>'total',
			'required'=>true,
			'type'=>\TARS::INT32,
			),
		self::ITEMS => array(
			'name'=>'items',
			'required'=>true,
			'type'=>\TARS::VECTOR,
			),
	);

	public function __construct() {
		parent::__construct('PaySystem_PayService_PayObj_OutPosBill', self::$_fields);
		$this->items = new \TARS_Vector(new PosBill());
	}
}
