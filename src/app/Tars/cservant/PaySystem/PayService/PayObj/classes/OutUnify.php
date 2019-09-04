<?php

namespace App\Tars\cservant\PaySystem\PayService\PayObj\classes;

class OutUnify extends \TARS_Struct {
	const ORDERSN = 0;


	public $ordersn; 


	protected static $_fields = array(
		self::ORDERSN => array(
			'name'=>'ordersn',
			'required'=>true,
			'type'=>\TARS::STRING,
			),
	);

	public function __construct() {
		parent::__construct('PaySystem_PayService_PayObj_OutUnify', self::$_fields);
	}
}
