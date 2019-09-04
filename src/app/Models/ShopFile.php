<?php

namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;

class ShopFile extends  Authenticatable
{
    protected $table = 'shop_file';
    protected $fillable = [
        'store_id',
        'name',
        'url'
    ];
    //邦邦总后台店铺资料文件上传[{\"id\":1,\"url\":\"a.png\"},{\"id\":2,\"url\":\"a.png\"}]
    public static function platUpload($store_id,$file_id,$file_url,$name,$type,$dellar,$file_type){
        if($type==3){//删除
           $res= self::where('id',$file_id)->delete();
           return ['code'=>200,'data'=>'删除成功'];
        }else if($type==4){//更新文件
            $data=[
                'name'=>$name,
                'url'=>$file_url,
                'file_type'=>$file_type
            ];
            $res= self::where('id',$file_id)->update($data);
            return ['code'=>200,'data'=>'更新成功'];
        }else if($type==5){
            $dellar=json_decode($dellar,true);
            for($i=0;$i<count($dellar);$i++){
                $res= self::where('id',$dellar[$i])->delete();
            }
            return ['code'=>200,'data'=>'删除成功'];
        }else{ //添加文件
            $data = [
                'store_id' => $store_id,
                'url' => $file_url,
                'name' => $name,
                'file_type'=>$file_type
            ];
            $res = self::where('id', $file_id)->insert($data);
            return ['code' => 200, 'data' => '上传成功'];
        }
    }
    //文件列表
    public static function platUploadList($store_id,$rows,$search){
        $query=self::where('store_id',$store_id);
        if($search){
            $query=$query->where('name','like','%'.$search.'%');
        }
        return   ['code'=>200,'data'=>$query->paginate($rows)];
    }
}