<?php

namespace App\Tars\cservant\PaySystem\PayService\PayObj\classes;

class InPosAmount extends \TARS_Struct {
	const START_AT = 0;
	const END_AT = 1;
	const POS_ID = 2;


	public $start_at; 
	public $end_at; 
	public $pos_id; 


	protected static $_fields = array(
		self::START_AT => array(
			'name'=>'start_at',
			'required'=>false,
			'type'=>\TARS::STRING,
			),
		self::END_AT => array(
			'name'=>'end_at',
			'required'=>false,
			'type'=>\TARS::STRING,
			),
		self::POS_ID => array(
			'name'=>'pos_id',
			'required'=>false,
			'type'=>\TARS::INT32,
			),
	);

	public function __construct() {
		parent::__construct('PaySystem_PayService_PayObj_InPosAmount', self::$_fields);
	}
}
