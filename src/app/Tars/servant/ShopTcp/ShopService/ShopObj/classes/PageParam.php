<?php

namespace App\Tars\servant\ShopTcp\ShopService\ShopObj\classes;

class PageParam extends \TARS_Struct {
	const PAGE = 0;
	const PAGESIZE = 1;
	const IDARRAY = 2;
	const SEARCH = 3;


	public $page; 
	public $pageSize; 
	public $idArray; 
	public $search; 


	protected static $_fields = array(
		self::PAGE => array(
			'name'=>'page',
			'required'=>false,
			'type'=>\TARS::INT32,
			),
		self::PAGESIZE => array(
			'name'=>'pageSize',
			'required'=>false,
			'type'=>\TARS::INT32,
			),
		self::IDARRAY => array(
			'name'=>'idArray',
			'required'=>false,
			'type'=>\TARS::STRING,
			),
		self::SEARCH => array(
			'name'=>'search',
			'required'=>false,
			'type'=>\TARS::STRING,
			),
	);

	public function __construct() {
		parent::__construct('ShopTcp_ShopService_ShopObj_PageParam', self::$_fields);
	}
}
