<?php

namespace App\Tars\cservant\PaySystem\PayService\PayObj\classes;

class InOrderPage extends \TARS_Struct {
	const PAGE = 0;
	const ROWS = 1;
	const SEARCH = 2;


	public $page=1; 
	public $rows=10; 
	public $search; 


	protected static $_fields = array(
		self::PAGE => array(
			'name'=>'page',
			'required'=>true,
			'type'=>\TARS::INT32,
			),
		self::ROWS => array(
			'name'=>'rows',
			'required'=>true,
			'type'=>\TARS::INT32,
			),
		self::SEARCH => array(
			'name'=>'search',
			'required'=>false,
			'type'=>\TARS::STRING,
			),
	);

	public function __construct() {
		parent::__construct('PaySystem_PayService_PayObj_InOrderPage', self::$_fields);
	}
}
