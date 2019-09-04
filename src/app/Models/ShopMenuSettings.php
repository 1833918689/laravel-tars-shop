<?php

namespace App\Models;
use Illuminate\Http\Request;
class ShopMenuSettings extends  Authenticatable
{
    protected $table = 'shop_menu_settings';
    protected $fillable = [
        'name',
        'pid',
        'lavel',
        'path',
    ];

    /*
     * @邦邦店铺菜单账号权限详情
     * type 1添加 2查询 3更新 4删除
    */
    public static function platMenuList($request){
        $type       =$request->input('type','1');          //1添加 2查询 3更新 4删除
        if($type==1||$type==3||$type==4){ //1添加 2查询 3更新 4删除
            $name     =$request->input('name','');       //菜单名称
            if(!$name)   return ['code'=>400,'msg'=>'菜单名称不能为空'];
            $pid      =$request->input('pid','-1');      //上级id
            $lavel    =$request->input('lavel','1');      //菜单等级
            $path     =$request->input('path','');       //菜单路由
            $id       =$request->input('id','');           //更新才有这个字段
        }else if($type==2){
           $res= self::select();
           $res= self::list_to_tree($res,'id','pid');
           return ['code'=>200,'data'=>$res];
        }
        $data=[
            'name'=>$name,
            'pid'=> $pid,
            'lavel'=> $lavel,
            'path'=> $path,
        ];
        switch ($type){
            case 1: //添加
               $res=self::create($data);
               break;
            case 3: //更新
                if(!$id)   return ['code'=>400,'msg'=>'id不能为空'];
                $res=self::where('id',$id)->update($data);
                break;
            case 4:  //删除
                if(!$id)   return ['code'=>400,'msg'=>'id不能为空'];
                $res=self::where('id',$id)->delete($data);
                break;
        }
        if($res){
            return ['code'=>200,'data'=>'成功'];
        }else{
            return ['code'=>200,'data'=>'失败'];
        }
    }
    /**
     * 把返回的数据集转换成Tree
     * @access public
     * @param array $list 要转换的数据集
     * @param string $pid parent标记字段
     * @param string $level level标记字段
     * @return array
     */
    public static function  list_to_tree($list, $pk = 'id', $pid = 'pid', $child = '_child', $root = -1)
    {
        // 创建Tree
        $tree = array();
        if (is_array($list)) {
            // 创建基于主键的数组引用
            $refer = array();
            foreach ($list as $key => $data) {
                $refer[$data[$pk]] =& $list[$key];// $data[$pk] 获取id   $list[$key]获取查询出来的第一个数组值
            }  //第一个循环是把查询出数组的键值改为自己的Id
            foreach ($list as $key => $data) {
                // 判断是否存在parent
                $parentId = $data[$pid];  //获取pid
                if ($root == $parentId) {
                    $tree[] =& $list[$key]; //如果等于-1，那么我就把这里当作父级
                } else {
                    $parent =& $refer[$parentId];
                    $parent[$child][] =& $list[$key];
                }
            }
        }
        return $tree;
    }
    /*
     *@商家菜单设置权限添加查询
     */
}