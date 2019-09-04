<?php

namespace App\Tars\cservant\BB\UserService\User\classes;

class UserAccount extends \TARS_Struct {
	const UUID = 0;
	const ACCOUNT = 1;
	const MOBILE = 3;
	const CREATEDAT = 4;
	const ENVDOMAINID = 5;
	const PASSWORD = 7;


	public $uuid; 
	public $account; 
	public $mobile; 
	public $createdAt; 
	public $envDomainId; 
	public $password; 


	protected static $_fields = array(
		self::UUID => array(
			'name'=>'uuid',
			'required'=>true,
			'type'=>\TARS::STRING,
			),
		self::ACCOUNT => array(
			'name'=>'account',
			'required'=>true,
			'type'=>\TARS::STRING,
			),
		self::MOBILE => array(
			'name'=>'mobile',
			'required'=>true,
			'type'=>\TARS::STRING,
			),
		self::CREATEDAT => array(
			'name'=>'createdAt',
			'required'=>true,
			'type'=>\TARS::STRING,
			),
		self::ENVDOMAINID => array(
			'name'=>'envDomainId',
			'required'=>true,
			'type'=>\TARS::INT32,
			),
		self::PASSWORD => array(
			'name'=>'password',
			'required'=>true,
			'type'=>\TARS::STRING,
			),
	);

	public function __construct() {
		parent::__construct('BB_UserService_User_UserAccount', self::$_fields);
	}
}
