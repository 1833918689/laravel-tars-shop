<?php

namespace App\Tars\cservant\OrderSystem\OrderServer\OrderObj\classes;

class InUpdateOrder extends \TARS_Struct {
	const ID = 0;
	const PHONE = 1;
	const STATUS = 2;
	const REMARK = 3;
	const EXPRESS_CODE = 4;


	public $id; 
	public $phone; 
	public $status; 
	public $remark; 
	public $express_code; 


	protected static $_fields = array(
		self::ID => array(
			'name'=>'id',
			'required'=>true,
			'type'=>\TARS::INT32,
			),
		self::PHONE => array(
			'name'=>'phone',
			'required'=>false,
			'type'=>\TARS::STRING,
			),
		self::STATUS => array(
			'name'=>'status',
			'required'=>false,
			'type'=>\TARS::INT32,
			),
		self::REMARK => array(
			'name'=>'remark',
			'required'=>false,
			'type'=>\TARS::STRING,
			),
		self::EXPRESS_CODE => array(
			'name'=>'express_code',
			'required'=>false,
			'type'=>\TARS::STRING,
			),
	);

	public function __construct() {
		parent::__construct('OrderSystem_OrderServer_OrderObj_InUpdateOrder', self::$_fields);
	}
}
