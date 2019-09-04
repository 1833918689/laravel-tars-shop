<?php

namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;

class ShopConfig extends  Authenticatable
{
    protected $table = 'shop_config';
    protected $fillable = [
        'store_id',
        'app_id',
        'secret',
        'aes_key'
    ];
    //获取公众号的配置
    public static function  config($shop_id){
       return  self::where('store_id',$shop_id)->first();
    }
}