<?php

namespace App\Tars\cservant\PaySystem\PayService\PayObj\classes;

class InUnify extends \TARS_Struct {
	const AMOUNT = 0;
	const MARK = 1;
	const REMARK = 2;
	const UUID = 3;
	const STATE = 4;
	const OPENID = 5;


	public $amount; 
	public $mark; 
	public $remark; 
	public $uuid; 
	public $state; 
	public $openid; 


	protected static $_fields = array(
		self::AMOUNT => array(
			'name'=>'amount',
			'required'=>true,
			'type'=>\TARS::INT32,
			),
		self::MARK => array(
			'name'=>'mark',
			'required'=>true,
			'type'=>\TARS::STRING,
			),
		self::REMARK => array(
			'name'=>'remark',
			'required'=>true,
			'type'=>\TARS::STRING,
			),
		self::UUID => array(
			'name'=>'uuid',
			'required'=>true,
			'type'=>\TARS::STRING,
			),
		self::STATE => array(
			'name'=>'state',
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
		parent::__construct('PaySystem_PayService_PayObj_InUnify', self::$_fields);
	}
}
