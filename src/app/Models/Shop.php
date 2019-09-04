<?php

namespace App\Models;

use App\Tars\cservant\OrderSystem\OrderServer\OrderObj\classes\OrderGoods;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use mysql_xdevapi\Exception;
use Tars\client\CommunicatorConfig;
use App\Tars\cservant\BB\UserService\User\UserServant;
use App\Tars\cservant\BB\UserService\User\classes\UserBasic;
use App\Tars\cservant\BB\UserService\User\classes\UserInfo;
use App\Models\TaobaoCategory;

use App\Tars\cservant\OrderSystem\OrderServer\OrderObj\OrderServant;
use App\Tars\cservant\OrderSystem\OrderServer\OrderObj\classes\InNotifyData;
use App\Tars\cservant\OrderSystem\OrderServer\OrderObj\classes\resultMsg;
use App\Tars\cservant\OrderSystem\OrderServer\OrderObj\classes\InSearch;
use App\Tars\cservant\OrderSystem\OrderServer\OrderObj\classes\OutOrderList;
use App\Tars\cservant\OrderSystem\OrderServer\OrderObj\classes\OutOrderInfo;
use App\Tars\cservant\OrderSystem\OrderServer\OrderObj\classes\InUpdateOrder;

use App\Tars\cservant\IntegralSystem\IntegralServer\IntegralObj\IntegralTafServiceServant;
use App\Tars\cservant\IntegralSystem\IntegralServer\IntegralObj\classes\resultMsg as InterresultMsg;

use App\Tars\cservant\PaySystem\PayService\PayObj\PayServiceServant;
class Shop extends  Authenticatable
{
    protected $table = 'shop';
    protected $fillable = [
        'uid',
        'name',
        'surname',
        'industry',
        'industry_id',
        'type',
        'phone',
        'wechat',
        'qq',
        'province',
        'city',
        'district',
        'detail',
        'istrial',
        'status',
        'domain',
        'domain_id',
        'expiretime',
        'shoptype',
        'is_auth',
        'pid',
        'mobile'
    ];
    public static function config($request,$token){
        $config = new CommunicatorConfig();
        $config->setLocator('tars.tarsregistry.QueryObj@tcp -h 172.18.5.59 -p 17890');
        $config->setModuleName('BB.UserService');
        $config->setCharsetName('UTF-8');
        $user_basic = new UserInfo();
        $path_info=$request->getPathInfo();
        (new UserServant($config))->getUserInfoByToken($token,$path_info,$user_basic,$error);
        return $user_basic;
    }
    /*
     * @添加试用版店铺店铺(多店跟单店)
     * 添加试用版店铺不用提交审核信息
     */
    public static  function addshop($request)
    {
        $name       =$request->input('name','');
        $industry   =$request->input('industry','');
        $province   =$request->input('province','');  //省
        $city       =$request->input('city','');      //店铺所在的市
        $district   =$request->input('district','');  //区
        $detail     =$request->input('detail','');    //详细地址
        $identif    =$request->input('identif',0);    //是否绑定微信公众号
        $shoptype   =$request->input('shoptype',0);   //店铺类型0是单商家 1是多商家
        $pid        =$request->input('pid',0);   //用户创建多店
        $surname        =$request->input('surname','');   //名称
        $industry_id   =$request->input('industry_id','');   //店铺行业分类id
        $domain='';
        if($shoptype<1||!$shoptype){                               //单商家
            $domain     =$request->input('domain',''); //域名
            if(!$domain){
                $domain=self::generate($domain);     //生成二级域名
            }else{
                $domain=$domain.'.bbddp.com';
                $testing=self::testing($domain);
                if($testing['code']!=200){
                    return $testing;
                }
            }
        }

        $token = $request->header('X-API-Key');
        $user=self::config($request,$token);
        $uuid=$user->uuid;
        $mobile=$user->mobile;
        if(!$uuid){
            return ['code'=>400,'msg'=>'uuid不存在或者token已经过期'];
        }
        if($name){
            $data=[
                'uid'=>$uuid,
                'name'=> $name,
                'industry'=> $industry,
                'industry_id'=>$industry_id,
                'type'=> 3,            //1正式 3试用开店(体验）
                'province'=> $province,
                'city'=> $city,
                'district'=> $district,
                'detail'=> $detail,
                'istrial'=> 1,        //0:未试用；1试用中  2：已试用
                'identif'=> $identif,
                'status'=>7,    //0待审核，客户经理待确认 1绑定支付商户号 2绑定微信号 3域名更换 4审核完成 5打烊 6审核失败
                'domain_id'=>$user->envDomainId,
                'domain'=>$domain,
                'expiretime'=>time()+(3600*7*24),
                'shoptype'=>$shoptype,
                'pid'=>$pid,
                'surname'=> $surname,
                'mobile'=>$mobile
            ];
            $res=self::where(['name'=>$name])->select()->get();
            if(count($res)>0){
                return ['code'=>400,'msg'=>'名字已经重复'];
            }else {
                $res =self::create($data);
                if($res){
                    self::where('id',$res->id)->update(['plat_id'=>$res->id]);
                    self::notifyShopRegister($res->id);//创建店铺积分账户
                    return ['code'=>200,'data'=>$res->id];
                }else{
                    return ['code'=>400,'msg'=>'添加失败'];
                }
            }
        }else{
            return ['code'=>400,'msg'=>'请填写分类名称'];
        }
    }
    //商家后台试用版店铺转正为正式版店铺
    public static function shopTurnJust($request){
        $request=Request();
        $token = $request->header('X-API-Key');
        $surname    =$request->input('surname','');    //真实姓名
        $phone      =$request->input('phone','');      //手机号
        $wechat     =$request->input('wechat','');     //微信号
        $qq         =$request->input('qq','');         //qq
        $industry   =$request->input('industry','');   //店铺行业分类
        $name       =$request->input('name','');       //店铺名称
        $province   =$request->input('province','');   //省
        $city       =$request->input('city','');       //市
        $district   =$request->input('district','');   //区
        $detail     =$request->input('detail','');     //详细地址
        $shoptype   =$request->input('shoptype','0');
        $pid        =$request->input('pid',0);   //用户创建多店
        $store_id=$request->input('store_id','');
        $industry_id   =$request->input('industry_id','');   //店铺行业分类id
        $domain='';
        if($shoptype<1||!$shoptype){                               //单商家
            $domain     =$request->input('domain',''); //域名
            if(!$domain){
                $domain=self::generate($domain);     //生成二级域名
            }else{
                $domain=$domain.'bbddp.com';
                $testing=self::testing($domain);
                if($testing['code']!=200){
                    return $testing;
                }
            }
        }

        $user=self::config($request,$token);
        $uuid=$user->uuid;
        $mobile=$user->mobile;
        if(!$uuid){
            return ['code'=>400,'msg'=>'uuid不存在或者token已经过期'];
        }
        if($name){
            $data=[
                'uid'=>$uuid,
                'type'=> 1,
                'surname'=> $surname,
                'phone'=> $phone,
                'wechat'=> $wechat,
                'qq'=> $qq,
                'industry'=> $industry,
                'industry_id'=>$industry_id,
                'name'=> $name,
                'province'=> $province,
                'city'=> $city,
                'district'=> $district,
                'istrial'=> 2,
                'detail'=> $detail,
                'status'=>0,
                'domain'=>$domain,
                'shoptype'=>$shoptype,
                'pid'=>$pid,
                'mobile'=>$mobile
            ];
            $res=self::where(['id'=>$store_id])->update($data);
            if($res){
                return ['code'=>200,'msg'=>'提交完成'];
            }else{
                return ['code'=>400,'msg'=>'提交失败'];
            }
        }else{
            return ['code'=>400,'msg'=>'请填写分类名称'];
        }

    }
    //随机生成域名的前缀
   public static function getRandomString($len, $chars=null)
    {
        if (is_null($chars)){
            $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        }
        mt_srand(10000000*(double)microtime());
        for ($i = 0, $str = '', $lc = strlen($chars)-1; $i < $len; $i++){
            $str .= $chars[mt_rand(0, $lc)];
        }
        return $str;
    }
    //检测域名是否重合
    public static function testing($domain){
        $res=self::where(['domain'=>$domain])->select()->get()->toArray();
        if(count($res)>0){
           return ['code'=>400,'msg'=>'域名重复，请重新填写'];
        }else{
            return ['code'=>200,'data'=>'成功'];
        }
    }
    //后端自动生成二级域名
    public static function generate(){
        $code = rand(2, 10);
        $domain=self::getRandomString($code);
        $domain=$domain.'.bbddp.com';
        $testing=self::testing($domain);
        if($testing['code']!=200) {
            $domain= self::generate();
        }
        return $domain;
    }
    /*
    * @添加正式版店铺店铺
    * 添加正式版店铺提交审核信息
    */
    public static  function addFormalShop($request)
    {
        $token = $request->header('X-API-Key');
        $surname    =$request->input('surname','');    //真实姓名
        $phone      =$request->input('phone','');      //手机号
        $wechat     =$request->input('wechat','');     //微信号
        $qq         =$request->input('qq','');         //qq
        $industry   =$request->input('industry','');   //店铺行业分类
        $industry_id   =$request->input('industry_id','');   //店铺行业分类id
        $name       =$request->input('name','');       //店铺名称
        $province   =$request->input('province','');   //省
        $city       =$request->input('city','');       //市
        $district   =$request->input('district','');   //区
        $detail     =$request->input('detail','');     //详细地址
        $shoptype   =$request->input('shoptype','0');
        $pid        =$request->input('pid',0);   //用户创建多店
        $domain='';
        if($shoptype<1||!$shoptype){                               //单商家
            $domain     =$request->input('domain',''); //域名
            if(!$domain){
                $domain=self::generate($domain);     //生成二级域名
            }else{
                $domain=$domain.'bbddp.com';
                $testing=self::testing($domain);
                if($testing['code']!=200){
                    return $testing;
                }
            }
        }

        $user=self::config($request,$token);
        $uuid=$user->uuid;
        $mobile=$user->mobile;
        if(!$uuid){
            return ['code'=>400,'msg'=>'uuid不存在或者token已经过期'];
        }
        if($name){ //
            $data=[
                'uid'=>$uuid,
                'type'=> 1,
                'surname'=> $surname,
                'phone'=> $phone,
                'wechat'=> $wechat,
                'qq'=> $qq,
                'industry'=> $industry,
                'industry_id'=>$industry_id,
                'name'=> $name,
                'province'=> $province,
                'city'=> $city,
                'district'=> $district,
                'istrial'=> 0,
                'detail'=> $detail,
                'status'=>0,
                'domain'=>$domain,
                'shoptype'=>$shoptype,
                'pid'=>$pid,
                'mobile'=>$mobile
            ];
            $res=self::where(['name'=>$name])->select()->get();
            if(count($res)>0){
                return ['code'=>400,'msg'=>'名字已经重复'];
            }else {
                $res = self::create($data);
                if($res){
                    self::where('id',$res->id)->update(['plat_id'=>$res->id]);
                    self::notifyShopRegister($res->id);//创建店铺积分账户
                    return ['code'=>200,'data'=>'添加成功'];
                }else{
                    return ['code'=>400,'msg'=>'添加失败'];
                }
            }
        }else{
            return ['code'=>400,'msg'=>'请填写分类名称'];
        }
    }
    //商家添加子店铺
    public static function addSubshop($request,$store_id,$phone,$surname,$name,$sub_id,$type,$delarr){
        $token = $request->header('X-API-Key');
        $user=self::config($request,$token);
        $uuid=$user->uuid;
        //$mobile=$user->mobile;
        if(!$uuid) return ['code'=>400,'msg'=>'uuid不存在或者token已经过期'];
        $industry= self::where('id',$store_id)->value('industry');
        $industry_id= self::where('id',$store_id)->value('industry_id');
        $data=[
            'uid'=>$uuid,
            'surname'=> $surname,
            'mobile'=> $phone,
            'name'=> $name,
            'pid'=>$store_id,
            'industry'=>$industry,
            'industry_id'=>$industry_id,
        ];
        if($type==1){
            $data['type']=0;
            $data['istrial']=0;
            $data['status']=0;
            $data['shoptype']=1;
            $res=self::where(['name'=>$name])->select()->get();
            if(count($res)>0)  return ['code' => 400, 'msg' => '名字已经重复'];

            $res = self::create($data);
            if($res){
                self::where('id',$res->id)->update(['plat_id'=>$store_id]);
                self::notifyShopRegister($res->id);//创建店铺积分账户
                return ['code'=>200,'data'=>'添加成功'];
            }else{
                return ['code'=>400,'msg'=>'添加失败'];
            }
        }else if($type==2){
            if(!$sub_id)  return ['code' => 400, 'msg' => 'sub_id不能为空'];
            $res = self::where('id',$sub_id)->update($data);
            return ['code'=>200,'data'=>'编辑成功'];
        }else{
            $delarr=json_decode($delarr,true);
            for($i=0;$i<count($delarr);$i++){
                $res = self::where('id',$delarr[$i])->delete();
            }
            return ['code'=>200,'data'=>'删除成功'];
        }
    }
    /*
     *@子店铺完善资料 查询多店铺的行业分类
     * type 为1时查询 ，2为添加完成子店铺资料
    */
    public static function shopSubList($request,$pid,$store_id,$type){
        if($type==1){//查询
            $industry= self::where('id',$pid)->value('industry');
            $name= self::where('id',$store_id)->value('name');
            return ['code'=>200,'data'=>['name'=>$name,'industry'=>$industry]];
        }else{ //添加完成子店铺资料
            $name       =$request->input('name','');
            $province   =$request->input('province','');  //省
            $city       =$request->input('city','');      //店铺所在的市
            $district   =$request->input('district','');  //区
            $detail     =$request->input('detail','');    //详细地址
            $token = $request->header('X-API-Key');
            $user=self::config($request,$token);
            $uuid=$user->uuid;
            if(!$uuid){
                return ['code'=>400,'msg'=>'uuid不存在或者token已经过期'];
            }
            $data=[
                'uid'=>$uuid,
                'name'=> $name,
                'type'=>1,            //1正式 3试用开店(体验）
                'province'=> $province,
                'city'=> $city,
                'district'=> $district,
                'detail'=> $detail,
                'istrial'=> 1,        //0:未试用；1试用中  2：已试用
                'identif'=> 1,
                'status'=>4,    //0待审核，客户经理待确认 1绑定支付商户号 2绑定微信号 3域名更换 4审核完成 5打烊 6审核失败
                'domain_id'=>$user->envDomainId,
                'shoptype'=>1,
            ];
            $res=self::where('id',$store_id)->update($data);
            if($res){
                return ['code'=>200,'data'=>'成功'];
            }else{
                return ['code'=>400,'msg'=>'失败'];
            }
        }
    }
    //查询某个商家下面有几个店
    public static function userShop($request){
        $token = $request->header('X-API-Key');
        $user=self::config($request,$token);
        $mobile=$user->mobile;
        $uuid=$user->uuid;
        if(!$mobile){
            return ['code'=>400,'msg'=>'未绑定手机号'];
        }
        $search= $request->input('search','');
        $rows= $request->input('rows','10');
        $res=self::where(['mobile'=>$mobile]);
        if($search){
            $res=$res->where('name','like','%'.$search.'%');
        }
        $collection=$res->paginate($rows);
        $collection->getCollection()->transform(function ($value)use($uuid) {
            if($value->expiretime){
                $value->expiretime = date('Y-m-d h:i:s ', $value->expiretime);
            }
            $value->integral  = self::integralBalance($uuid,$value->id);//剩余积分
            //$value->mechantOverage = self::mechantOverage($value->id); //商家剩余金额
            return $value;
        });
        return ['code'=>200,'data'=>$collection];
    }
    //查询多店版下面有几个店铺
    public static function userShopMany($request){
        $token = $request->header('X-API-Key');
        $user=self::config($request,$token);
        $uuid=$user->uuid;
        if(!$uuid){
            return ['code'=>400,'msg'=>'uuid不存在或者token已经过期'];
        }
        $pid        =$request->input('pid',0);   //用户创建多店
        $starttime  =$request->input('starttime',0);
        $endtime    =$request->input('endtime','');
        $search     = $request->input('search','');
        $rows    = $request->input('rows','');
        $res=self::where(['uid'=>$uuid])->where('pid',$pid);
        if($search){
            $res=$res->where('name','like','%'.$search.'%');
        }

        if($starttime){
            $res=  $res->where('created_at','>=',$starttime);
        }
        if($endtime){
            $res= $res->where('created_at','<=',$endtime);
        }
        $collection=$res->paginate($rows);
        $collection->getCollection()->transform(function ($value)use($uuid) {
            if($value->expiretime){
                $value->expiretime = date('Y-m-d h:i:s ', $value->expiretime);
            }
            $value->integral  = self::integralBalance($uuid,$value->id);//剩余积分
            //$value->mechantOverage = self::mechantOverage($value->id); //商家剩余金额
            return $value;
        });
        return ['code'=>200,'data'=>$collection];
    }

    //查询平台的下面提交审核的店铺

    //tars tcp查询
    //0审核；1支付商户号 2绑定微信号 3域名更换 4审核完成 5打烊
    public static function shopList($page,$row){
        $collection=self::orderBy('created_at','desc')->offset($page)->limit($row)->get();
        foreach($collection as $value){
            if($value->expiretime){
                $value->expiretime = date('Y-m-d h:i:s ', $value->expiretime);
            }
            switch($value->istrial){
                case 0:
                    $value->istrial = "未试用";
                case 1:
                    $value->istrial = "已试用";
            }
            switch($value->status){
                case 0:
                    $value->status = "等待客户经理对接";
                    $value->operation = "跟踪客户";

                case 1:
                    $value->status = "待绑支付商户号";
                    $value->operation = "提醒客户";
                case 2:
                    $value->status = "绑定微信号";
                    $value->operation = "提醒客户";
                    break;
                case 3:
                    $value->status = "待域名更换";
                    $value->operation = "待域名更换";

                case 4:
                    $value->status = "审核完成";
                    $value->operation = "查看详情";

                case 5:
                    $value->status = "打烊";
                    $value->operation = "查看详情";
            }
        }
        return $collection;
    }

    //0审核；1支付商户号 2绑定微信号 3域名更换 4审核完成 5打烊
    public static function platSelectShop($row){
        $collection=self::orderBy('created_at','desc')->paginate($row);
        $collection->getCollection()->transform(function ($value) {
            if($value->expiretime){
                $value->expiretime = date('Y-m-d h:i:s ', $value->expiretime);
            }
            switch($value->istrial){
                case 0:
                    $value->istrial = "未试用";
                case 1:
                    $value->istrial = "已试用";
            }
            switch($value->status){
                case 0:
                    $value->status = "等待客户经理对接";
                    $value->operation = "跟踪客户";

                case 1:
                    $value->status = "待绑支付商户号";
                    $value->operation = "提醒客户";
                case 2:
                    $value->status = "绑定微信号";
                    $value->operation = "提醒客户";
                    break;
                case 3:
                    $value->status = "待域名更换";
                    $value->operation = "待域名更换";

                case 4:
                    $value->status = "审核完成";
                    $value->operation = "查看详情";

                case 5:
                    $value->status = "打烊";
                    $value->operation = "查看详情";
            }
            return $value;
        });
        return $collection;
    }
    //查询平台中某家商家得信息
    public static function platSelectShopOne($store_id){
        $res=self::find($store_id);
        if($res['istrial']==0){
            $res['istrial']='未试用';
        }else{
            $res['istrial']='已试用';
        }
        $res['integral']       = self::integralBalance($res['uid'],$store_id);//剩余积分
//        $res['mechantOverage'] = self::mechantOverage($store_id);//商家剩余金额
        return $res;
    }
    //更新平台中某家商家得信息
    public static function platDockingShop($store_id,$contacts){
        $res=self::where('id',$store_id)->update(['contacts'=>$contacts]);
        if($res){
            return ['code'=>200,'data'=>'成功'];
        }else{
            return ['code'=>400,'msg'=>'失败'];
        }
        return $res;
    }
    //平台的店铺管理列表
    public static function platShoplist($row,$search,$shoptype,$start_time,$end_time,$type){
        {
            $query=  $collection=self::orderBy('created_at','desc');
            if($search){
                $query=$query->where('name','like','%'.$search.'%');
            }
            if($shoptype){
                switch ($shoptype){
                    case 1://单店铺
                        $query=$query->where('shoptype',0);
                        break;
                    case 2://多店铺
                        $query=$query->where('shoptype',1);
                        break;
                    case 3://多店铺的子店铺
                        $query=$query->where('pid','>',0);
                        break;
                }
            }
            if($start_time){
                $query=  $query->where('start_time','>=',$start_time);
            }
            if($end_time){
                $query=  $query->where('end_time','<=',$end_time);
            }
            if($type){
                $query=  $query->where('type',$type);
            }
            $collection=$query->whereIn('status', [4,5])->paginate($row);
            $collection->getCollection()->transform(function ($value) {
                switch($value->status){
                    case 0:
                        $value->status = "等待客户经理对接";
                        break;
                    case 1:
                        $value->status = "待绑支付商户号";
                        break;
                    case 2:
                        $value->status = "绑定微信号";
                        break;
                    case 3:
                        $value->status = "待域名更换";
                        break;
                    case 4:
                        $value->status = "营业中";
                        break;
                    case 5:
                        $value->status = "打烊";
                        break;
                }
                if($value->pid>0){
                    $value->shopcard='子店铺';
                }else{
                   $ty= self::where('pid',$value->id)->select()->get()->toArray();
                   if($ty){
                       $value->shopcard='多店铺';
                   }else{
                       $value->shopcard = "单店铺";
                   }
                }
                return $value;
            });
            return $collection;
        }
    }
    //平台的客户列表店铺列表
    public static function platUserShopList($row,$search,$istrial,$start_time,$end_time,$type){
        {
            $query= self::orderBy('created_at','desc');
            if($search){
                $query=  $query->where(function($query)use($search)
                {
                    $query->where(['phone'=>['like','%'.$search.'%']])
                        ->Orwhere(['surname'=>['like','%'.$search.'%']]);
                });
            }
            if($start_time){
                $query=  $query->where('start_time','>=',$start_time);
            }
            if($end_time){
                $query=  $query->where('end_time','<=',$end_time);
            }
            if($type==1){
                $query=$query->where('status',0);
            }
            if($type==2){
                $query=$query->whereIn('status', [1, 2, 3]);
            }
            if($type==3){
                $query=$query->where('status',4);
            }
            if($istrial){
                $query=$query->where('istrial',$istrial-1);
            }
            $collection=$query->paginate($row);
            $collection->getCollection()->transform(function ($value) {
                $state=ShopExamineLog::where('store_id',$value->id)
                    ->where('status',$value->status)->value('state');
                switch($value->status){
                    case 0:
                        if(!$state){
                            $value->status = "客户经理对接-未联系";
                        }
                        if($state==1){
                            $value->status = "客户经理对接-已联系";
                        }
                        if($state==2){
                            $value->status = "客户经理对接-未接通";
                        }
                        if($state==3){
                            $value->status = "客户经理对接-其他";
                        }
                        $value->operation="跟踪客户";
                        break;
                    case 1:
                        if($state==1){
                            $value->status = "支付商户号-等待收集资料";
                        }
                        if($state==2){
                            $value->status = "支付商户号-等待审核";
                        }
                        if($state==3){
                            $value->status = "支付商户号-审核通过";
                        }
                        if($state==4){
                            $value->status = "支付商户号-其他";
                        }
                        $value->operation="处理进度";
                        break;
                    case 2:
                        if($state==1){
                            $value->status = "绑定微信号-正在开通公众号";
                        }
                        if($state==2){
                            $value->status = "绑定微信号-设置公众号";
                        }
                        if($state==3){
                            $value->status = "绑定微信号-已完成";
                        }
                        if($state==4){
                            $value->status = "绑定微信号-其他";
                        }
//                        $value->status = "绑定微信号";
                        $value->operation="处理进度";
                        break;
                    case 3:
                        if($state==1){
                            $value->status = "域名更换-申请域名中";
                        }
                        if($state==2){
                            $value->status = "域名更换-部署域名中";
                        }
                        if($state==3){
                            $value->status = "域名更换-已完成";
                        }
                        if($state==4){
                            $value->status = "域名更换-其他";
                        }
                        $value->operation="处理";
                        break;
                    case 4:
                        $value->status = "已完成";
                        $value->operation="查看详情";
                        break;
                    case 5:
                        $value->status = "打烊";
                        break;
                }
                return $value;
            });
            return $collection;
        }
    }
    /*
 *@平台
 *@获取店铺的状态 以及店铺数量
 */
    public static function shopStatus(){
        $audited =self::where('status',0)->where('pid',0)->select()->count();//待审核新用户
        $inreview=self::whereIn('status', [1, 2, 3])->where('pid',0)->count();
        $complete=self::where('status',4)->where('pid',0)->select()->count();//审核完成
        $data=[
            'audited'=>$audited,
            'inreview'=>$inreview,
            'complete'=>$complete,
        ];
        return $data;
    }
    //查询平台设置的分类
    public  static function platformType(){
        $res=TaobaoCategory::select()->where('level','<=','2')->get()->toArray();
        $channe= Category::list_to_tree($res, 'id', 'parents_id');
        return $channe;
    }

    //查询平台的商家列表
    public static function  demain_id($store_id){
        $domain_id=self::where('id',$store_id)->value('domain_id');
        return $domain_id;
    }
    //查询商家订单列表

    public static function orderList($request){
        $config = new CommunicatorConfig();
        $config->setLocator('tars.tarsregistry.QueryObj@tcp -h 172.18.5.59 -p 17890');
        $config->setModuleName('OrderSystem.OrderServer');
        $config->setCharsetName('UTF-8');
        try{
            $OrderServant= new OrderServant($config);
            $InSearch= new InSearch();
            $InSearch->rows     =$request->input('rows',10);
            $InSearch->search   =$request->input('search','');
            $InSearch->shop_id  =$request->input('shop_id','');
            $InSearch->start    =$request->input('start','');//开始时间
            $InSearch->end      =$request->input('end','');//结束时间
            $InSearch->order_id =$request->input('order_id','');
            if($InSearch->shop_id){
                $InSearch->env_domain_id =self::demain_id($InSearch->shop_id);
                $InSearch->env_domain_id =self::demain_id($InSearch->shop_id);
            }else{
                return ['code'=>400,'msg'=>'店铺id不能为空'];
            }
            $token = $request->header('X-API-Key');
            $user=self::config($request,$token);
            $uuid=$user->uuid;
          //  $InSearch->uuid=$uuid;

            $OrderList= new OutOrderList();
            $OutOrderInfo= new OutOrderInfo();
            $resultMsg= new resultMsg();
            $return=[];
            $OrderServant->OrderList($InSearch, $OrderList, $resultMsg);
            if($resultMsg->code==200){
                $return['total']= $OrderList->total;
                $return['items']=$OrderList->items;
                return $return;
            }else{
                return $resultMsg;
            }
        }catch (Exception $e){
            return $e;
        }
    }
    //查询商家某条订单详情OrderInfo($id,OutOrderInfo &$OrderInfo,resultMsg &$result)
    public static function OrderInfo($id){
        $config = new CommunicatorConfig();
        $config->setLocator('tars.tarsregistry.QueryObj@tcp -h 172.18.5.59 -p 17890');
        $config->setModuleName('OrderSystem.OrderServer');
        $config->setCharsetName('UTF-8');
        $OrderServant= new OrderServant($config);
        $OutOrderInfo= new OutOrderInfo();
        $resultMsg=new resultMsg();
        $OrderServant->OrderInfo($id,$OutOrderInfo,$resultMsg);
        if ($resultMsg->code=200){
            $data = [
                'id' => $OutOrderInfo->id,
                'ordersn' => $OutOrderInfo->ordersn,
                'recipient' => $OutOrderInfo->recipient,
                'address' => $OutOrderInfo->address,
                'phone' => $OutOrderInfo->phone,
                'paid_at' => $OutOrderInfo->paid_at,
                'status' => $OutOrderInfo->status,
                'amount' => $OutOrderInfo->amount,
                'gift_points' => $OutOrderInfo->gift_points,
                'goods' => $OutOrderInfo->goods,
            ];
            return $data;
        }else{
            return $resultMsg;
        }
    }
    /*
     * @tcp接口
     * @创建店铺积分账户
    */
    public static function notifyShopRegister($shop_id){
        $config = new CommunicatorConfig();
        $config->setLocator('tars.tarsregistry.QueryObj@tcp -h 172.18.5.59 -p 17890');
        $config->setModuleName('IntegralSystem.IntegralServer');
        $config->setCharsetName('UTF-8');
        $IntegralSystem= new IntegralTafServiceServant($config);
        $OurParams= new InterresultMsg();
        $IntegralSystem-> notifyShopRegister($shop_id,$OurParams);
        return;
    }
    /*
     *@获取店铺剩余积分
     */
    public static function integralBalance($uuid,$shop_id){
        $config = new CommunicatorConfig();
        $config->setLocator('tars.tarsregistry.QueryObj@tcp -h 172.18.5.59 -p 17890');
        $config->setModuleName('IntegralSystem.IntegralServer');
        $config->setCharsetName('UTF-8');
        $IntegralSystem= new IntegralTafServiceServant($config);
        $OurParams= new InterresultMsg();
        $IntegralSystem->integralBalance($uuid,$shop_id,$points,$OurParams);
        return $points;
    }
    /*
     *@获取店铺剩余金额
     * mechantOverage($shopId,&$overage)
     */
    public static function mechantOverage($shopId){
        $config = new CommunicatorConfig();
        $config->setLocator('tars.tarsregistry.QueryObj@tcp -h 172.18.5.59 -p 17890');
        $config->setModuleName('PaySystem.PayService');
        $config->setCharsetName('UTF-8');
        $PayService= new PayServiceServant($config);
        $PayService->mechantOverage($shopId,$overage);
        return;
    }
    /*
    *@商家后台设置店铺信息
    */
    public static function shopInfo($store_id){
        $shopInfo=self::where('id',$store_id)->first(['name','istrial','created_at','thumb','details','phone','identif']);
        return $shopInfo;
    }
    /*
    *@商家后台设置店铺信息
     *@$type  1 店铺名称 2logo 3手机号码更新
    */
    public static function shopInfoUpdate($store_id,$request){
        $data=[
            'name'=>$request->input('name',''),
            'thumb'=>$request->input('thumb',''),
            'phone'=>$request->input('phone',''),
            'details'=>$request->input('details','')
        ];
        $shopInfo=self::where('id',$store_id)->update($data);
        if($shopInfo){
            return ['code'=>200,'data'=>'更新成功'];
        }else{
            return ['code'=>400,'data'=>'更新失败'];
        }
    }

}