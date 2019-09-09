<?php

namespace App\Tars\impl;
use App\Tars\servant\ShopTcp\ShopService\ShopObj\ShopServant;
use App\Tars\servant\ShopTcp\ShopService\ShopObj\classes\PageParam;
use App\Tars\servant\ShopTcp\ShopService\ShopObj\classes\ShopList;
use App\Tars\servant\ShopTcp\ShopService\ShopObj\classes\ShopInfo;
use App\Tars\servant\ShopTcp\ShopService\ShopObj\classes\resultMsg;
use App\Models\Shop as store;
use App\Models\ShopConfig as ShopConfig;
use App\Models\TaobaoCategory;
use App\Models\ShopStatistics;
class ShopTcp  implements ShopServant{
    //平台下的店铺列表
    public function shopList(PageParam $Param,ShopList &$List){
        try{
            $page=$Param->page;
            $pageSize=$Param->pageSize==0?10:$Param->pageSize;
            $collection=store::shopList($page,$pageSize);
            $shopInfo=new ShopInfo();
            foreach ($collection as $key=>$value){
                $shopInfo->id         =$value->id;
                $shopInfo->thumb      =$value->thumb;
                $shopInfo->name       =$value->name;
                $shopInfo->type       =$value->type;
                $shopInfo->phone      =$value->phone;
                $shopInfo->uid        =$value->uid;
                $shopInfo->expiretime =$value->expiretime;
                $shopInfo->pid        =$value->pid;
                $shopInfo->surname    =$value->surname;
                $shopInfo->industry_name    =TaobaoCategory::where('id',$value->industry_id)->value('name');
                $shopInfo->istrial    =$value->istrial;
                $shopInfo->domain_id  =$value->domain_id;
                $List->item->pushBack($shopInfo);
            }
            $List->total==store::count();
            $List->code=200;
            return;
        }catch (\Exception $e){
            $List->code=400;
            $List->message=$e->getMessage();
            return;
        }
    }
    //店铺详情
    public function shopInfo($id,ShopInfo &$info){
        try{
            $paramas= (new store())->find($id);
            if(!$id){
                $info->code = 400;
                $info->message ='缺少id';
                return;
            }
            $info->id         =$paramas->id;
            $info->thumb      =$paramas->thumb;
            $info->name       =$paramas->name;
            $info->type       =$paramas->type;
            $info->phone      =$paramas->phone;
            $info->uid        =$paramas->uid;
            $info->expiretime =$paramas->pid;
            $info->surname    =$paramas->surname;
            $info->industry_name    =TaobaoCategory::where('id',$paramas->industry_id)->value('name');
            $info->istrial    =$paramas->istrial;
            $info->domain_id  =$paramas->domain_id;
            $info->code       =200;
            $info->message    ='成功';
            return;
        }catch (\Throwable $e){
            $info->code = 400;
            $info->message = $e->getMessage();
            return;
        }
    }
    //通过uuid判断店铺是否存在
    public function shopExis($uuid,resultMsg &$data){
        $paramas= (new store())->where(['uid'=>$uuid])->select()->get()->toArray();
        if(count($paramas)>0){
            $data->code=200;
            $data->msg='存在';
            $data->data=1;
        }else{
            $data->code=200;
            $data->msg='不存在';
            $data->data=0;
        }
        return $data;
    }
    //获取店铺域名domain_id
    public function shopDomainId($shop_id,resultMsg &$data){
        try {
            $domain_id = (new store())->where(['id' => $shop_id])->value('domain_id');
            $data->code = 200;
            $data->data = $domain_id;
        }catch (\Throwable $e){
            $data->code = 400;
            $data->msg = $e->getMessage();
            return;
        }
    }
    //获取店铺域名appid
    public function shopGetAppid($domain,resultMsg &$data){
        try {
            $store_id = (new store())->where(['domain' => $domain])->value('id');
            if($store_id){
                $app_id= (new ShopConfig())->where(['store_id'=>$store_id])->value('app_id');
                if($app_id){
                    $data->code = 200;
                    $data->data = $app_id;
                }else{
                    $data->code = 400;
                    $data->msg ='该店铺还未完善配置';
                    return;
                }
            }else{
                $data->code = 400;
                $data->msg ='不存在';
                return;
            }
        }catch (\Throwable $e){
            $data->code = 400;
            $data->msg = $e->getMessage();
            return;
        }

    }
    ///通过uuid获取店铺信息 getUuidShop($uuid,$demain_id,$type,ShopInfo &$ShopInfo);
    public function getUuidShop($uuid,$demain_id,ShopInfo &$ShopInfo){
        $paramas = (new store())->where(['uid' => $uuid,'domain_id'=>$demain_id])->first();
        if($paramas){
            $ShopInfo->id         =$paramas->id;
            $ShopInfo->thumb      =$paramas->thumb;
            $ShopInfo->name       =$paramas->name;
            $ShopInfo->type       =$paramas->type;
            $ShopInfo->phone      =$paramas->phone;
            $ShopInfo->uid        =$paramas->uid;
            $ShopInfo->expiretime =$paramas->pid;
            $ShopInfo->surname    =$paramas->surname;
            $ShopInfo->industry_name    =TaobaoCategory::where('id',$paramas->industry_id)->value('name');
            $ShopInfo->istrial    =$paramas->istrial;
            $ShopInfo->domain_id  =$paramas->domain_id;
            $ShopInfo->code       =200;
            $ShopInfo->message    ='成功';
        }else{
            $ShopInfo->code       =200;
            $ShopInfo->message    ='店铺不存在';
        }
        return;
    }
    //判断该店铺是否属于他的店铺
    public function isMyShop($uuid,$shop_id,resultMsg &$data){
        $paramas = (new store())->where(['uid' => $uuid,'id'=>$shop_id])->first();
        if($paramas){
            $data->code       =200;
            $data->data    =1;
            $data->msg    ='店铺存在';
        }else{
            $data->code       =200;
            $data->data    =0;
            $data->msg    ='店铺不存在';
        }
        return;
    }
}