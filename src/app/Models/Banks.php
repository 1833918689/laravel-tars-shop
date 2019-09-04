<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Banks extends  Authenticatable
{
    public static function selectBanks($bank_name,$city){
        $query=self::query();
        if($bank_name){
            $query=$query->where(['bank_name'=>$bank_name]);
        }
        if($city){
            $query=$query->where(['city'=>$city]);
        }
        $res=$query->select()->get()->toArray();
        return $res;
    }
}




?>