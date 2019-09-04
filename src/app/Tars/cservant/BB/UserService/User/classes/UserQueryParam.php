<?php

namespace App\Tars\cservant\BB\UserService\User\classes;

class UserQueryParam extends \TARS_Struct {
	const ACCOUNT = 1;
	const NICKNAME = 2;
	const MOBILE = 3;
	const CREATEDAT = 4;
	const ENVDOMAINID = 5;


	public $account; 
	public $nickname; 
	public $mobile; 
	public $createdAt; 
	public $envDomainId; 


	protected static $_fields = array(
		self::ACCOUNT => array(
			'name'=>'account',
			'required'=>false,
			'type'=>\TARS::STRING,
			),
		self::NICKNAME => array(
			'name'=>'nickname',
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
			'type'=>\TARS::STRUCT,
			),
		self::ENVDOMAINID => array(
			'name'=>'envDomainId',
			'required'=>false,
			'type'=>\TARS::INT32,
			),
	);

	public function __construct() {
		parent::__construct('BB_UserService_User_UserQueryParam', self::$_fields);
		$this->createdAt = new TimeQueryParam();
	}
}
