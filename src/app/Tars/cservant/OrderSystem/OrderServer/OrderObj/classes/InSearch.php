<?php

namespace App\Tars\cservant\OrderSystem\OrderServer\OrderObj\classes;

class InSearch extends \TARS_Struct {
	const PAGE = 0;
	const ROWS = 1;
	const SEARCH = 2;
	const UUID = 3;
	const SHOP_ID = 4;
	const START = 5;
	const END = 6;
	const ORDER_ID = 7;
	const ENV_DOMAIN_ID = 8;


	public $page; 
	public $rows= 10; 
	public $search; 
	public $uuid; 
	public $shop_id; 
	public $start; 
	public $end; 
	public $order_id; 
	public $env_domain_id; 


	protected static $_fields = array(
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
		self::SEARCH => array(
			'name'=>'search',
			'required'=>false,
			'type'=>\TARS::STRING,
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
		self::START => array(
			'name'=>'start',
			'required'=>false,
			'type'=>\TARS::STRING,
			),
		self::END => array(
			'name'=>'end',
			'required'=>false,
			'type'=>\TARS::STRING,
			),
		self::ORDER_ID => array(
			'name'=>'order_id',
			'required'=>false,
			'type'=>\TARS::INT32,
			),
		self::ENV_DOMAIN_ID => array(
			'name'=>'env_domain_id',
			'required'=>true,
			'type'=>\TARS::INT32,
			),
	);

	public function __construct() {
		parent::__construct('OrderSystem_OrderServer_OrderObj_InSearch', self::$_fields);
	}
}
