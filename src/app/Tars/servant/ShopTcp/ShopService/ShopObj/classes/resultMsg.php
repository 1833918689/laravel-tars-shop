<?php

namespace App\Tars\servant\ShopTcp\ShopService\ShopObj\classes;

class resultMsg extends \TARS_Struct {
	const CODE = 0;
	const MSG = 1;
	const DATA = 2;


	public $code; 
	public $msg; 
	public $data; 


	protected static $_fields = array(
		self::CODE => array(
			'name'=>'code',
			'required'=>false,
			'type'=>\TARS::INT32,
			),
		self::MSG => array(
			'name'=>'msg',
			'required'=>false,
			'type'=>\TARS::STRING,
			),
		self::DATA => array(
			'name'=>'data',
			'required'=>false,
			'type'=>\TARS::STRING,
			),
	);

	public function __construct() {
		parent::__construct('ShopTcp_ShopService_ShopObj_resultMsg', self::$_fields);
	}
}
