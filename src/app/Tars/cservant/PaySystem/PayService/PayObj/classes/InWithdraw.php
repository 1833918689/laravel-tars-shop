<?php

namespace App\Tars\cservant\PaySystem\PayService\PayObj\classes;

class InWithdraw extends \TARS_Struct {
	const OPENID = 0;
	const AMOUNT = 1;
	const UUID = 2;
	const SHOP_ID = 3;


	public $openid; 
	public $amount; 
	public $uuid; 
	public $shop_id; 


	protected static $_fields = array(
		self::OPENID => array(
			'name'=>'openid',
			'required'=>true,
			'type'=>\TARS::STRING,
			),
		self::AMOUNT => array(
			'name'=>'amount',
			'required'=>true,
			'type'=>\TARS::INT32,
			),
		self::UUID => array(
			'name'=>'uuid',
			'required'=>false,
			'type'=>\TARS::STRING,
			),
		self::SHOP_ID => array(
			'name'=>'shop_id',
			'required'=>false,
			'type'=>\TARS::INT32,
			),
	);

	public function __construct() {
		parent::__construct('PaySystem_PayService_PayObj_InWithdraw', self::$_fields);
	}
}
