<?php
namespace App\Tars\impl;
use App\Models\Shop as store;
use App\Models\TaobaoCategory;
class ShopPhoneHttp
{
    /**@商家后台start*/
    //查询某个商家下面有几个店
    public function userShop(){
        $request=Request();
        $res = store::userShop($request);
        return json_encode($res);
    }
    //查询多店版下面有几个店铺
    public function userShopMany(){
        $request=Request();
        $res = store::userShopMany($request);
        return json_encode($res);
    }
    //查询店铺信息
    public function phoneSelectShop(){
        $request=Request();
        $store_id=$request->input('store_id','');
        $res=store::find($store_id);
        if($res['industry']){
            $res['dustryname']= TaobaoCategory::where('id',$res['industry'])->value('name');
        }
        return json_encode(['code'=>200,'data'=>$res]);
    }
}
