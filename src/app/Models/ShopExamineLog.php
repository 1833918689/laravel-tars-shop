<?php

namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Tars\client\CommunicatorConfig;
use App\Tars\cservant\BB\UserService\User\UserServant;
use App\Tars\cservant\BB\UserService\User\classes\UserInfo;
use App\Models\Shop as store;
class ShopExamineLog extends  Authenticatable
{
    protected $table = 'shop_examine_log';
    protected $fillable = [
        'store_id',
        'status',
        'state',
        'content'
    ];
    //平台查询审核店铺状态
    public static function examine($store_id){
        $examine=self::where('store_id',$store_id)->select()->get()->toArray();
        if(!$examine){
            $examine[0]['status']=0;
            $examine[0]['state']=0;
            $examine[0]['content']='';
            $examine[0]['contacts']='';
        }else{
           foreach ($examine as $key=>$value){
               $examine[$key]['contacts']= store::where('id',$store_id)->value('contacts');
               $examine[$key]['status']=(int) $value['status'];
               $examine[$key]['state']=(int) $value['state'];
               $examine[$key]['content']=$value['content'];
           }
        }
        return $examine;
    }
    //平台进行审核店铺状态修改  (审核完成需要总后台设置店铺的时间)
    public static function changeStatus($store_id,$status,$state,$content){
        $res=self::where('store_id',$store_id)->where(['status'=>$status])->select()->get()->toArray();
        if($state==1){//更新店铺状态
            $re=store::where('id',$store_id)->update(['status'=>$status,'updated_at'=>now()]);
            if(!$re){
                return ['code'=>400,'data'=>'更新店铺状态失败'];
            }
        }
        $data=['state'=>(int)$state, 'content'=>$content];
        if($res){
            $res=self::where('store_id',$store_id)->where(['status'=>$status])->update($data);
        }else{
            $data=[
                'store_id'=> $store_id,
                'status'=>(int) $status,
                'state'=>(int)$state,
                'content'=>$content
            ];
            $res=self::insert($data);
        }
        if($res){
            return ['code'=>200,'data'=>'成功'];
        }else{
            return ['code'=>400,'data'=>'失败'];
        }
    }
    //审核完成
    public static function toExamine($store_id){
        $res=self::where('store_id',$store_id)->where(['status'=>4])->select()->get()->toArray();
        if(!$res){
            $data=[
                'store_id'=> $store_id,
                'status'=>5,
                'state'=>1
            ];
            $res=self::insert($data);
        }
        return true;
    }
}