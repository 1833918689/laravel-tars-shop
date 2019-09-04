<?php

namespace App\Tars\cservant\PaySystem\PayService\PayObj\classes;

class InPosBill extends \TARS_Struct {
	const START_AT = 0;
	const END_AT = 1;
	const PAYMODE = 2;
	const POS_ID = 3;
	const PAGE = 4;
	const ROWS = 5;


	public $start_at; 
	public $end_at; 
	public $paymode; 
	public $pos_id; 
	public $page=1; 
	public $rows=10; 


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
		self::PAYMODE => array(
			'name'=>'paymode',
			'required'=>false,
			'type'=>\TARS::INT32,
			),
		self::POS_ID => array(
			'name'=>'pos_id',
			'required'=>false,
			'type'=>\TARS::INT32,
			),
		self::PAGE => array(
			'name'=>'page',
			'required'=>false,
			'type'=>\TARS::INT32,
			),
		self::ROWS => array(
			'name'=>'rows',
			'required'=>false,
			'type'=>\TARS::INT32,
			),
	);

	public function __construct() {
		parent::__construct('PaySystem_PayService_PayObj_InPosBill', self::$_fields);
	}
}
