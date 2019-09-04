<?php

namespace App\Tars\cservant\BB\UserService\User\classes;

class UsersQueryParam extends \TARS_Struct {
	const UUID = 0;
	const ACCOUNT = 1;
	const NICKNAME = 2;
	const MOBILE = 3;
	const CREATEDAT = 4;
	const ENVDOMAINID = 5;


	public $uuid; 
	public $account; 
	public $nickname; 
	public $mobile; 
	public $createdAt; 
	public $envDomainId; 


	protected static $_fields = array(
		self::UUID => array(
			'name'=>'uuid',
			'required'=>false,
			'type'=>\TARS::VECTOR,
			),
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
			'required'=>true,
			'type'=>\TARS::INT32,
			),
	);

	public function __construct() {
		parent::__construct('BB_UserService_User_UsersQueryParam', self::$_fields);
		$this->createdAt = new TimeQueryParam();
		$this->uuid = new \TARS_Vector(\TARS::STRING);
	}
}
