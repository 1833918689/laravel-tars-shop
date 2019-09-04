<?php

namespace App\Tars\servant\ShopTcp\ShopService\ShopObj;

use App\Tars\servant\ShopTcp\ShopService\ShopObj\classes\PageParam;
use App\Tars\servant\ShopTcp\ShopService\ShopObj\classes\ShopList;
use App\Tars\servant\ShopTcp\ShopService\ShopObj\classes\ShopInfo;
use App\Tars\servant\ShopTcp\ShopService\ShopObj\classes\resultMsg;
interface ShopServant {
	/**
	 * @param struct $Param \App\Tars\servant\ShopTcp\ShopService\ShopObj\classes\PageParam
	 * @param struct $List \App\Tars\servant\ShopTcp\ShopService\ShopObj\classes\ShopList =out=
	 * @return void
	 */
	public function shopList(PageParam $Param,ShopList &$List);
	/**
	 * @param int $id 
	 * @param struct $info \App\Tars\servant\ShopTcp\ShopService\ShopObj\classes\ShopInfo =out=
	 * @return void
	 */
	public function shopInfo($id,ShopInfo &$info);
	/**
	 * @param string $uuid 
	 * @param struct $data \App\Tars\servant\ShopTcp\ShopService\ShopObj\classes\resultMsg =out=
	 * @return void
	 */
	public function shopExis($uuid,resultMsg &$data);
	/**
	 * @param int $shop_id 
	 * @param struct $data \App\Tars\servant\ShopTcp\ShopService\ShopObj\classes\resultMsg =out=
	 * @return void
	 */
	public function shopDomainId($shop_id,resultMsg &$data);
	/**
	 * @param string $domain 
	 * @param struct $data \App\Tars\servant\ShopTcp\ShopService\ShopObj\classes\resultMsg =out=
	 * @return void
	 */
	public function shopGetAppid($domain,resultMsg &$data);
	/**
	 * @param string $uuid 
	 * @param int $demain_id 
	 * @param struct $ShopInfo \App\Tars\servant\ShopTcp\ShopService\ShopObj\classes\ShopInfo =out=
	 * @return void
	 */
	public function getUuidShop($uuid,$demain_id,ShopInfo &$ShopInfo);
	/**
	 * @param string $uuid 
	 * @param int $shop_id 
	 * @param struct $data \App\Tars\servant\ShopTcp\ShopService\ShopObj\classes\resultMsg =out=
	 * @return void
	 */
	public function isMyShop($uuid,$shop_id,resultMsg &$data);
}

