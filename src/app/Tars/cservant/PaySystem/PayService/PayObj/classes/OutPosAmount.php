<?php

namespace App\Tars\cservant\PaySystem\PayService\PayObj\classes;

class OutPosAmount extends \TARS_Struct {
	const ITEMS = 0;


	public $items; 


	protected static $_fields = array(
		self::ITEMS => array(
			'name'=>'items',
			'required'=>true,
			'type'=>\TARS::VECTOR,
			),
	);

	public function __construct() {
		parent::__construct('PaySystem_PayService_PayObj_OutPosAmount', self::$_fields);
		$this->items = new \TARS_Vector(new PosAmount());
	}
}
