<?php

namespace App\Tars\cservant\PaySystem\PayService\PayObj\classes;

class PosAmount extends \TARS_Struct {
	const POS_ID = 0;
	const AMOUNT = 1;


	public $pos_id; 
	public $amount; 


	protected static $_fields = array(
		self::POS_ID => array(
			'name'=>'pos_id',
			'required'=>true,
			'type'=>\TARS::INT32,
			),
		self::AMOUNT => array(
			'name'=>'amount',
			'required'=>true,
			'type'=>\TARS::INT32,
			),
	);

	public function __construct() {
		parent::__construct('PaySystem_PayService_PayObj_PosAmount', self::$_fields);
	}
}
