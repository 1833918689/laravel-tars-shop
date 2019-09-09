<?php
namespace App\Tars\impl;
use App\Models\ShopConfig;
use Illuminate\Contracts\Container\Container;
use Illuminate\Http\Request;
use App\Models\Shop as store;
use App\Models\ShareModel;
use App\Models\Banks;
use App\Models\ShopService;
use App\Models\ShopInfo;
use App\Models\ShopExamineLog;
use  App\Models\ShopMenuSettings;
use App\Models\ShopFile;
use App\Models\ShopStatistics;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use  SimpleSoftwareIO\QrCode\Facades\QrCode;
use EasyWeChat\Factory;
use Illuminate\Support\Facades\Redis;
use App\Models\TaobaoCategory;
class ShopHttp
{
    /**@商家后台start*/
    //添加试用版店铺信息
    public function addTrialShop()
    {
        $request=Request();
        $res = store::addshop($request);
        return json_encode($res);
    }
    //正式订购-创建店铺流程
    public function addFormalShop(){
        $request=Request();
        $res = store::addFormalShop($request);
        return json_encode($res);
    }

    //商家后台试用版店铺转正为正式版店铺
    public function shopTurnJust(){
        $request=Request();
        $store_id=$request->input('store_id','');
        if(!$store_id) return json_encode(['code'=>400,'msg'=>'缺少店铺id']);
        $res=store::shopTurnJust($request);
        return json_encode($res);
    }
    //商家添加子店铺
    public function addSubshop(){
        $request=Request();
        $store_id=$request->input('store_id','');
        if(!$store_id) return json_encode(['code'=>400,'msg'=>'缺少店铺id']);
        $phone=$request->input('phone','');
        if(!$phone) return json_encode(['code'=>400,'msg'=>'负责人账号不能为空']);
        $surname=$request->input('surname','');
        if(!$surname) return json_encode(['code'=>400,'msg'=>'负责人姓名不能为空']);
        $name=$request->input('name','');
        if(!$name) return json_encode(['code'=>400,'msg'=>'店铺名称不能为空']);
        $sub_id=$request->input('sub_id','');
        $type=$request->input('type','1');
        $delarr=$request->input('delarr','1');
        $res=store::addSubshop($request,$store_id,$phone,$surname,$name,$sub_id,$type,$delarr);
        return json_encode($res);
    }
    //子店铺完善资料 查询多店铺的行业分类
    public function shopSubList(){
        $request=Request();
        $store_id=$request->input('store_id','');
        if(!$store_id) return json_encode(['code'=>400,'msg'=>'缺少店铺id']);
        $pid=$request->input('pid','');
        if(!$pid) return json_encode(['code'=>400,'msg'=>'上级店铺id不能为空']);
        $type=$request->input('type','1');
        $res= store::shopSubList($request,$pid,$store_id,$type);
        return json_encode($res);
    }
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
    public function selectShop(){
        $request=Request();
        $store_id=$request->input('store_id','');
        $res=store::find($store_id);
        if(!$store_id) return json_encode(['code'=>400,'msg'=>'缺少店铺id']);
        if($res['industry']){
            $res['industryname']= TaobaoCategory::where('id',$res['industry'])->value('name');
        }
        $shopconfig=ShopInfo::where('store_id',$store_id)->first();
        $res['shopconfig']=$shopconfig;
        return json_encode(['code'=>200,'data'=>$res]);
    }
    //完善店铺信息(提交审核资料)
    public function perfectShop(){
        $request=Request();
        $res=ShopInfo::perfectShop($request);
        return json_encode($res);
    }
    //绑定支付商户号
    public function merchant(){
        $request=Request();
        $store_id=$request->input('store_id','');
        if(!$store_id) return json_encode(['code'=>400,'msg'=>'缺少店铺id']);
        $merchant=$request->input('merchant','');
        $res=store::where('id',$store_id)->update(['merchant'=>$merchant,'status'=>1]);
        if($res){
            return json_encode(['code'=>200,'data'=>'成功']);
        }else{
            return json_encode(['code'=>400,'msg'=>'失败']);
        }
    }

    //更换域名
    public function replace(){
        $request=Request();
        $store_id=$request->input('store_id','');
        if(!$store_id) return json_encode(['code'=>400,'msg'=>'缺少店铺id']);
        $domain=$request->input('domain','');
        $res=store::where('id',$store_id)->update(['domain'=>$domain,'status'=>3]);
        if($res){
            return json_encode(['code'=>200,'data'=>'成功']);
        }else{
            return json_encode(['code'=>400,'msg'=>'失败']);
        }
    }
    //商家的公众号店铺配置
    public function app_config(){
        $request=Request();
        $store_id=$request->input('store_id','');
        if(!$store_id) return json_encode(['code'=>400,'msg'=>'缺少店铺id']);
        $app_id=$request->input('app_id','');
        $secret=$request->input('secret','');
        $aes_key=$request->input('aes_key','');
        if(!$store_id||!$app_id||!$secret||!$aes_key){
            return json_encode(['code'=>400,'msg'=>'缺少参数']);
        }else{
            $store=ShopConfig::where('store_id',$store_id)->select()->get()->toArray();
            $data=[
                'app_id'=>$app_id,
                'secret'=>$secret,
                'aes_key'=>$aes_key,
            ];
            if(count($store)>0){
                $res=ShopConfig::where('store_id',$store_id)->update($data);
            }else{
                $data['store_id']=$store_id;
                $res=ShopConfig::insert($data);
            }
            return json_encode(['code'=>200,'data'=>$res]);
        }
    }
    /*
    *@店铺设置手续费
    */
    public function shopRate(){
        $request=Request();
        $store_id=$request->input('store_id','');
        if(!$store_id) return json_encode(['code'=>400,'msg'=>'缺少店铺id']);
        $rate=$request->input('rate','');
        if(!$rate) return json_encode(['code'=>400,'msg'=>'设置费率']);
        $res=store::where('id',$store_id)->update(['rate'=>$rate]);
        if($res){
            return json_encode(['code'=>200,'data'=>'成功']);
        }else{
            return json_encode(['code'=>400,'msg'=>'失败']);
        }
    }
    //商家公众号的配置信息
    public function config_info(){
        $request=Request();
        $store_id=$request->input('store_id','');
        if(!$store_id) return json_encode(['code'=>400,'msg'=>'缺少店铺id']);
        $info=ShopConfig::where('store_id',$store_id)->first();
        return json_encode(['code'=>200,'data'=>$info]);
    }
    /*@商家后台end@*/
    /*@平台start@*/
    //查询平台设置的分类
    public function platformType(){
        $channe= store::platformType();
        return json_encode(['code'=>200,'data'=>$channe]);
    }
    //查询平台店铺列表
    public function platSelectShop(){
        $request=Request();
        $row=$request->input('row','20');
        $collection=store::platSelectShop($row);
        return json_encode(['code'=>200,'data'=>$collection]);
    }
    //查询某家店铺得详细信息
    public function platSelectShopOne(){
        $request=Request();
        $store_id=$request->input('store_id','');
        if(!$store_id) return json_encode(['code'=>400,'msg'=>'缺少店铺id']);
        $res=store::platSelectShopOne($store_id);
        return json_encode(['code'=>200,'data'=>$res]);
    }
    //平台确定审核，客户经理对接
    public function platDockingShop(){
        $request=Request();
        $store_id=$request->input('store_id','');
        if(!$store_id) return json_encode(['code'=>400,'msg'=>'缺少店铺id']);
        $contacts=$request->input('contacts','');
        if(!$contacts||!$store_id){
            return json_encode(['code'=>400,'msg'=>'缺少参数']);
        }
        $res=store::platDockingShop($store_id,$contacts);
        return json_encode(['code'=>200,'data'=>$res]);
    }
    /*
     *@平台查询审核店铺状态
     *status为0时（1已联系 2未接通 4其他）status为1时（1等待收集资料 2等待审核3审核通过4其他）status为2时（1正在开通公众号2设置公众号3已完成4其他）status为3时（1申请域名中2部署域名中3已完成4其他）status为4时（1开通设置 上传文件）
    */
    public function platAuditStatus(){
        $request=Request();
        $store_id=$request->input('store_id','');
        if(!$store_id) return json_encode(['code'=>400,'msg'=>'缺少店铺id']);
        $status  =ShopExamineLog::examine($store_id);
        return  json_encode(['code'=>200,'data'=>$status]);
    }
    /*
     *@平台进行审核店铺状态修改
     */
    public function platChangeStatus(){
        $request=Request();
        $store_id=$request->input('store_id','');
        if(!$store_id) return json_encode(['code'=>400,'msg'=>'缺少店铺id']);
        $status=$request->input('status','');     //店铺审核主状态
        $state=$request->input('state','');       //店铺审核子状态
        $content=$request->input('content','');  //点击其他获取的内容
        $res  =ShopExamineLog::changeStatus($store_id,$status,$state,$content);
        return  json_encode($res);
    }
    //平台设置店铺开店的有效期 审核完成
    public function platSettingTime(){
        $request=Request();
        $store_id=$request->input('store_id','');
        if(!$store_id) return json_encode(['code'=>400,'msg'=>'缺少店铺id']);
        $start_time=$request->input('start_time','');
        $end_time=$request->input('end_time','');
        if(!$start_time) return json_encode(['code'=>400,'msg'=>'设置店铺开始有效期不能为空']);
        if(!$end_time) return json_encode(['code'=>400,'msg'=>'设置店铺结束有效期不能为空']);
        $res=store::where('id',$store_id)->update(['start_time'=>$start_time,'end_time'=>$end_time,'status'=>4]);
        //只有设置了时间店铺才才是审核完成
        $re= ShopExamineLog::toExamine($store_id);
        if($re){
            $data=[
                'start_time'=>$start_time,
                'end_time'=>$end_time,
            ];
            return json_encode(['code'=>200,'data'=>$data]);
        }else{
            return json_encode(['code'=>400,'msg'=>'失败']);
        }
    }
    /*
     *@平台的店铺管理列表(shenhe)
     */
    public function platShoplist(){
        $request=Request();
        $row=$request->input('row','20');
        $search=$request->input('search','');
        $shoptype=$request->input('shoptype','');
        $start_time=$request->input('start_time','');
        $end_time=$request->input('end_time','');
        $type=$request->input('type','1');//1正式 3试用开店(体验）
        $res = store::platShoplist($row,$search,$shoptype,$start_time,$end_time,$type);
        return json_encode(['code'=>200,'data'=>$res]);
    }

    /*
     *@平台的客户列表店铺列表
     * @search 搜索电话号码\名称
     * @istrial 1:未试用；2试用中  3：已试用
     */
    public function platUserShopList(){
        $request=Request();
        $row=$request->input('row','20');
        $search=$request->input('search','');
        $istrial=$request->input('istrial','');
        $start_time=$request->input('start_time','');
        $end_time=$request->input('end_time','');
        $type=$request->input('type','1');
        $res = store::platUserShopList($row,$search,$istrial,$start_time,$end_time,$type);
        return json_encode(['code'=>200,'data'=>$res]);
    }
    //平台店铺状态总数
    public function shopStatus(){
        $data=store::shopStatus();
        return  json_encode(['code'=>200,'data'=>$data]);
    }
    //切换店铺 (该店是单店还是多店铺)
    public function switchShop(){
        $request=Request();
        $store_id=$request->input('store_id','');
        if($store_id){
            $type=store::where(['id'=>$store_id])->value('pid');
            if($type>0){
                return json_encode(['code'=>200,'data'=>1]);//多店
            }else{
                return json_encode(['code'=>200,'data'=>2]);//单店
            }
        }else{
            return json_encode(['code'=>400,'msg'=>'缺少参数']);
        }
    }
    //公众号授权给微信开放平台  wxc4ac1f3f73d6c004  57b798f74f97979b771be8da763892c5
    public function generate(){
        //$configure=ShopConfig::config($store_id);
        $config = [
            'app_id'   => 'wx47e4079b45bc6ee2',
            'secret'   => 'fb437915a4bd8340a5f5cc8e8a0477a7',
            'token'    => 'i89jijlkj97564DRet48',
            'aes_key'  => 'iasd90394IUIW9ewrjlo23r09i0sdfklj90KJLKJiwe'
        ];
        $openPlatform = Factory::openPlatform($config);
//       return Cache::get("easywechat.open_platform.verify_ticket.wx47e4079b45bc6ee2");
        $url=$openPlatform->getPreAuthorizationUrl('https://www.bbddp.com/api/shop/test/callback');
        return json_encode(['code'=>200,'data'=>$url]);
    }
    //绑定微信公众号回调
    public function callback(){
        $request=Request();
        $store_id=$request->input('store_id','');
        $res=store::where('id',$store_id)->update(['status'=>2,'identif'=>1]);
        //跳转到前端页面，前端使用ajax来请求oauthLoginAttempt获得token
        $to='www.baidu.com';
        return redirect($to);
    }
    //查询搜索商家订单列表
    public function orderList(){
        $request=Request();
        $list= store::orderList($request);
        return json_encode($list);
    }
    //查询商家某条订单详情
    public function OrderInfo(){
        $request=Request();
        $id=$request->input('id','');
        $list= store::orderinfo($id);
        return json_encode(['code'=>200,'data'=>$list]);
    }
    //邦邦店铺菜单账号权限详情
    public function platMenuList(){
        $request=Request();
        $res= ShopMenuSettings::platMenuList($request);
        return json_encode($res);
    }
    //邦邦总后台店铺资料文件上传 1 上传 2列表 3删除 4更新 5批量删除
    public  function platUpload(){
        $request=request();
        $store_id=$request->input('store_id','');
        $file_id=$request->input('file_id','');
        $file_url=$request->input('file_url','');
        $name=$request->input('name','');
        $type=$request->input('type','1');
        $dellar=$request->input('delarr','');
        $file_type=$request->input('file_type','');
        if($type!=2){
            if($type==1||$type==4){
                if(!$store_id) return json_encode(['code'=>400,'msg'=>'缺少店铺id']);
                if(!$name) return json_encode(['code'=>400,'msg'=>'文件名称不能为空']);
                if(!$file_url) return json_encode(['code'=>400,'msg'=>'文件路径不能为空']);
            }
            $uplod=ShopFile::platUpload($store_id,$file_id,$file_url,$name,$type,$dellar,$file_type);
        }else{
            $rows=$request->input('rows','15');
            $search=$request->input('search','');
            $uplod=ShopFile::platUploadList($store_id,$rows,$search);
        }
        return json_encode($uplod);
    }
    /*@平台end@*/
    //分页查询
    public function shopList(){
        $list= store::shopList(1,2);
        return json_encode(['code'=>200,'data'=>$list]);
    }
    //微信公众号转发朋友圈朋友需要的参数
    public function forward(){
        $request=Request();
        $store_id=$request->input('store_id');
        $configure=ShopConfig::config($store_id);
        if(!$configure){
            return  json_encode(['code'=>400,'msg'=>'店铺配置未填写']);
        }
        $nonceStr = $this->createNonceStr(); //构造一个随机数，用来生成签名的一部分
        $timestamp=time();
        $url=$request->input('url');
        if(!$url){
            return json_encode(['code'=>400,'msg'=>'回调路径不能为空']);
        }

        $jsapiTicket = ShareModel::wx_get_jsapi_ticket();
        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url"; //签名算法先按照ascII码排序
        $signature = sha1($string);  //对排序好的字符串加密
        $signPackage = array(
            "debug"     =>true,
            "appId"     => $configure['app_id'],
            "nonceStr"  => $nonceStr,
            "timestamp" =>$timestamp,
            "signature" => $signature,
            "jsApiList" => ['onMenuShareTimeline', 'onMenuShareAppMessage', 'onMenuShareQQ', 'onMenuShareWeibo']
        );
        return json_encode(['code'=>200,'data'=>$signPackage]);
    }
    //构造一个随机数，用来生成签名的一部分
    private function createNonceStr($length = 16) { //生成随机16个字符的字符串
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }
    //模糊匹配银行
    public function banksVague(){
        $request=request();
        $bank_name= $request->input('bank_name','');//银行
        $city= $request->input('city','');//银行名称
        $banck=Banks::selectBanks($bank_name,$city);
        return  json_encode(['code'=>200,'data'=>$banck]);

    }

    //添加客服
    public function addCustomerService(){
        $request=request();
        $store_id        =$request->input('store_id','');   //客服电话
        $phone        =$request->input('phone','');   //客服电话
        $province       =$request->input('province','');   //所在地址省
        $city           =$request->input('city','');   //所在地址市
        $area           =$request->input('area','');    //居住区
        $address           =$request->input('address','');    //居住区
        $service=ShopService::addCustomerService($store_id,$phone,$province,$city,$area,$address);
        return  json_encode($service);
    }
    //佩服客服
    public function selectCustomerService(){
        $request=request();
        $store_id        =$request->input('store_id','');   //客服电话
        $service=ShopService::where('store_id',$store_id)->first();
        return  json_encode(['code'=>200,'data'=>$service]);
    }
    //商家后台设置店铺信息
    public function shopInfo(){
        $request=request();
        $store_id=$request->input('store_id','');
        if(!$store_id) return json_encode(['code'=>400,'msg'=>'缺少店铺id']);
        $res= store::shopInfo($store_id);
        return json_encode(['code'=>200,'data'=>$res]);
    }
    //商家后台设置店铺信息更改
    public function shopInfoUpdate(){
        $request=request();
        $store_id=$request->input('store_id','');
        if(!$store_id) return json_encode(['code'=>400,'msg'=>'缺少店铺id']);
        $res= store::shopInfoUpdate($store_id,$request);
        return json_encode(['code'=>200,'data'=>$res]);
    }
    //商家菜单设置权限添加查询
    public function shopMenuUpdate(){
        $request=request();
        $store_id=$request->input('store_id','');
        if(!$store_id) return json_encode(['code'=>400,'msg'=>'缺少店铺id']);
        $res= ShopMenuSettings::shopMenuUpdate($store_id);
    }
    //打烊店铺
    public function commonCloseShop(){
        $request=request();
        $type=$request->input('type','1');
        $param=$request->input('param','');
        if(!$param) return json_encode(['code'=>400,'msg'=>'缺少参数']);
        if($type==1){//单店打烊
            $res= store::where('id',$param)->update(['status'=>5]);
        }elseif($type==2){   //批量打烊
            $param=json_decode($param,true);
            for($i=0;$i<count($param);$i++){
                $res= store::where('id',$param[$i])->update(['status'=>5]);
            }
        }elseif($type==3){//开启营业
            $res= store::where('id',$param)->update(['status'=>4]);
        }else{
            $param=json_decode($param,true);
            for($i=0;$i<count($param);$i++){
                $res= store::where('id',$param[$i])->delete();
            }
        }
        return  json_encode(['code'=>200,'data'=>'成功']);
    }
    //查询数据统计
    public function shop_statistics(){
        $request=request();
        $rew=$request->input('rew','15');
        $start_time=$request->input('start_time','');
        $end_time=$request->input('end_time','');
        $search=$request->input('search','');
        $data=ShopStatistics::index($rew,$start_time,$end_time,$search);
        return  json_encode(['code'=>200,'data'=>$data]);
    }

}
