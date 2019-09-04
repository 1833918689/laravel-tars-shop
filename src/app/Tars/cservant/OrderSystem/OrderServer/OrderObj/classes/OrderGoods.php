<?php

namespace App\Tars\cservant\OrderSystem\OrderServer\OrderObj\classes;

class OrderGoods extends \TARS_Struct {
	const SUBJECT = 0;
	const ATTR = 1;
	const THUMB = 2;
	const NUM = 3;
	const PRICE = 4;


	public $subject; 
	public $attr; 
	public $thumb; 
	public $num; 
	public $price; 


	protected static $_fields = array(
		self::SUBJECT => array(
			'name'=>'subject',
			'required'=>true,
			'type'=>\TARS::STRING,
			),
		self::ATTR => array(
			'name'=>'attr',
			'required'=>true,
			'type'=>\TARS::STRING,
			),
		self::THUMB => array(
			'name'=>'thumb',
			'required'=>true,
			'type'=>\TARS::STRING,
			),
		self::NUM => array(
			'name'=>'num',
			'required'=>true,
			'type'=>\TARS::INT32,
			),
		self::PRICE => array(
			'name'=>'price',
			'required'=>true,
			'type'=>\TARS::FLOAT,
			),
	);

	public function __construct() {
		parent::__construct('OrderSystem_OrderServer_OrderObj_OrderGoods', self::$_fields);
	}
}
