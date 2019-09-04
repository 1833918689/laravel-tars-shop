<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class TaobaoCategory extends  Authenticatable
{
    protected $table='taobao_category';
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
}