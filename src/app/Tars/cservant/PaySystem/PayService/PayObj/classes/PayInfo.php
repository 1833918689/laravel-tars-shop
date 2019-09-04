<?php

namespace App\Tars\cservant\PaySystem\PayService\PayObj\classes;

class PayInfo extends \TARS_Struct {
	const UUID = 0;
	const ORDER_ID = 1;
	const OUT_ORDER_ID = 2;
	const MARK = 3;
	const AMOUNT = 4;
	const STATUS = 5;
	const ENV_DOMAIN_ID = 6;
	const FINISHED_AT = 7;
	const CREATED_AT = 8;
	const REMARK = 9;
	const OPENID = 10;


	public $uuid; 
	public $order_id; 
	public $out_order_id; 
	public $mark; 
	public $amount; 
	public $status; 
	public $env_domain_id; 
	public $finished_at; 
	public $created_at; 
	public $remark; 
	public $openid; 


	protected static $_fields = array(
		self::UUID => array(
			'name'=>'uuid',
			'required'=>true,
			'type'=>\TARS::INT32,
			),
		self::ORDER_ID => array(
			'name'=>'order_id',
			'required'=>true,
			'type'=>\TARS::STRING,
			),
		self::OUT_ORDER_ID => array(
			'name'=>'out_order_id',
			'required'=>false,
			'type'=>\TARS::STRING,
			),
		self::MARK => array(
			'name'=>'mark',
			'required'=>true,
			'type'=>\TARS::STRING,
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
		self::ENV_DOMAIN_ID => array(
			'name'=>'env_domain_id',
			'required'=>true,
			'type'=>\TARS::INT32,
			),
		self::FINISHED_AT => array(
			'name'=>'finished_at',
			'required'=>false,
			'type'=>\TARS::STRING,
			),
		self::CREATED_AT => array(
			'name'=>'created_at',
			'required'=>true,
			'type'=>\TARS::STRING,
			),
		self::REMARK => array(
			'name'=>'remark',
			'required'=>false,
			'type'=>\TARS::STRING,
			),
		self::OPENID => array(
			'name'=>'openid',
			'required'=>false,
			'type'=>\TARS::STRING,
			),
	);

	public function __construct() {
		parent::__construct('PaySystem_PayService_PayObj_PayInfo', self::$_fields);
	}
}
