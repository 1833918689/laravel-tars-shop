<?php

namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Tars\client\CommunicatorConfig;
use App\Tars\cservant\BB\UserService\User\UserServant;
use App\Tars\cservant\BB\UserService\User\classes\UserInfo;
class ShopService extends  Authenticatable
{
    protected $table = 'shop_service';
    protected $fillable = [
        'store_id',
        'phone',
        'province',
        'city',
        'area',
    ];
    public static function addCustomerService($store_id,$phone,$province,$city,$area,$address){
        $data=[
            'store_id'=>$store_id,
            'phone'=> $phone,
            'province'=> $province,
            'city'=> $city,
            'area'=> $area,
            'address'=> $address,
        ];
        $res=self::where('store_id',$store_id)->first();
        if($res){
            $inf= self::where('store_id',$store_id)->update($data);
        }else{
            $data['store_id']=$store_id;
            $inf= self::create($data);
        }
        if($inf){
            return ['code'=>200,'data'=>'成功'];
        }else{
            return ['code'=>400,'msg'=>'失败'];
        }
    }
}