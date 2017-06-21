<?php
namespace app\admin\validate;

use think\Validate;

class Category extends Validate
{
    protected $rule = [
        'cate_name' => 'require',
        'cate_pid' => 'require|number',
        'cate_sort' => 'require|number|between:1,9999'
    ];
    protected $message = [
        'cate_name.require' => '请输入栏目名称',
        'cate_pid.require' => '请选所属栏目',
        'cate_pid.number' => '所属栏目必须为数字',
        'cate_sort.require' => '请输入排序',
        'cate_sort.number' => '排序必须为数字',
        'cate_sort.between' => '排序需在1到9999之间'
    ];
}
