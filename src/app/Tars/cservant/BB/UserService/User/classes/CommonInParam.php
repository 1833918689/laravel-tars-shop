<?php

namespace App\Tars\cservant\BB\UserService\User\classes;

class CommonInParam extends \TARS_Struct {
	const ENVDOMAINID = 0;


	public $envDomainId; 


	protected static $_fields = array(
		self::ENVDOMAINID => array(
			'name'=>'envDomainId',
			'required'=>false,
			'type'=>\TARS::INT32,
			),
	);

	public function __construct() {
		parent::__construct('BB_UserService_User_CommonInParam', self::$_fields);
	}
}
