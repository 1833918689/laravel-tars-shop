<?php

namespace App\Tars\cservant\PaySystem\PayService\PayObj\classes;

class PosBill extends \TARS_Struct {
	const POS_ID = 0;
	const PAYMODE = 1;
	const AMOUNT = 2;
	const STATUS = 3;
	const ADDR = 4;
	const FINISHED_AT = 5;


	public $pos_id; 
	public $paymode; 
	public $amount; 
	public $status; 
	public $addr; 
	public $finished_at; 


	protected static $_fields = array(
		self::POS_ID => array(
			'name'=>'pos_id',
			'required'=>true,
			'type'=>\TARS::INT32,
			),
		self::PAYMODE => array(
			'name'=>'paymode',
			'required'=>true,
			'type'=>\TARS::INT32,
			),
		self::AMOUNT => array(
			'name'=>'amount',
			'required'=>true,
			'type'=>\TARS::INT32,
			),
		self::STATUS => array(
			'name'=>'status',
			'required'=>true,
			'type'=>\TARS::INT32,
			),
		self::ADDR => array(
			'name'=>'addr',
			'required'=>true,
			'type'=>\TARS::STRING,
			),
		self::FINISHED_AT => array(
			'name'=>'finished_at',
			'required'=>true,
			'type'=>\TARS::STRING,
			),
	);

	public function __construct() {
		parent::__construct('PaySystem_PayService_PayObj_PosBill', self::$_fields);
	}
}
