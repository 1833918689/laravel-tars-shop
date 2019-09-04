<?php

namespace App\Tars\cservant\BB\UserService\User\classes;

class UserBasic extends \TARS_Struct {
	const UUID = 0;
	const ACCOUNT = 1;
	const MOBILE = 2;
	const CREATEDAT = 3;
	const ENVDOMAINID = 4;


	public $uuid; 
	public $account; 
	public $mobile; 
	public $createdAt; 
	public $envDomainId; 


	protected static $_fields = array(
		self::UUID => array(
			'name'=>'uuid',
			'required'=>false,
			'type'=>\TARS::STRING,
			),
		self::ACCOUNT => array(
			'name'=>'account',
			'required'=>false,
			'type'=>\TARS::STRING,
			),
		self::MOBILE => array(
			'name'=>'mobile',
			'required'=>false,
			'type'=>\TARS::STRING,
			),
		self::CREATEDAT => array(
			'name'=>'createdAt',
			'required'=>false,
			'type'=>\TARS::STRING,
			),
		self::ENVDOMAINID => array(
			'name'=>'envDomainId',
			'required'=>false,
			'type'=>\TARS::INT32,
			),
	);

	public function __construct() {
		parent::__construct('BB_UserService_User_UserBasic', self::$_fields);
	}
}
