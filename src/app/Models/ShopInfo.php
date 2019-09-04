<?php

namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Tars\client\CommunicatorConfig;
use App\Tars\cservant\BB\UserService\User\UserServant;
use App\Tars\cservant\BB\UserService\User\classes\UserInfo;
class ShopInfo extends  Authenticatable
{
    protected $table = 'shop_info';
    protected $fillable = [
        'store_id',
        'uid',
        'surname',
        'idcard',
        'bankcard',
        'reservedname',
        'bankname',
        'cardpositive',
        'cardside',
        'bankprovince',
        'bankcity',
        'subbank',
        'province',
        'city',
        'area',
        'status',
        'subback',
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
    //完善店铺信息(提交审核资料)
    public static function perfectShop($request){
        $token = $request->header('X-API-Key');
        $user=self::config($request,$token);
        $uuid=$user->uuid;
        $store_id       =$request->input('store_id','');      //店铺id
        $surname        =$request->input('surname','');       //请输入真实姓名
        $idcard         =$request->input('idcard','');        //请输入身份证号
        $bankcard       =$request->input('bankcard','');      // 请输入银行卡号码
        $reservedname   =$request->input('reservedname','');  // 请输入银行卡预留姓名
        $bankname       =$request->input('bankname','');          //请输入银行所在行
        $cardpositive   =$request->input('cardpositive','');  //身份证正面
        $cardside       =$request->input('cardside','');      //身份证反面
        $bankprovince   =$request->input('bankprovince','');   //银行所在省
        $bankcity       =$request->input('bankcity','');   //银行所在市
        $subbank        =$request->input('subbank','');   //银行分支名称
        $province       =$request->input('province','');   //所在地址省
        $city           =$request->input('city','');   //所在地址市
        $area           =$request->input('area','');    //居住区
        $data=[
            'uid'=>$uuid,
            'surname'=> $surname,
            'idcard'=> $idcard,
            'bankcard'=> $bankcard,
            'reservedname'=> $reservedname,
            'bankname'=> $bankname,
            'cardpositive'=> $cardpositive,
            'cardside'=> $cardside,
            'bankprovince'=> $bankprovince,
            'bankcity'=> $bankcity,
            'subbank'=>$subbank,
            'province'=>$province,
            'city'=>$city,
            'area'=>$area,
            'status'=>0,
            'store_id'=>$store_id
        ];
        $res=self::where('store_id',$store_id)->first();
        if($res){
            $inf= self::where('store_id',$store_id)->update($data);
        }else{
            $inf= self::create($data);
        }
        if($inf){
            return ['code'=>200,'data'=>'成功'];
        }else{
            return ['code'=>400,'msg'=>'失败'];
        }
    }

}