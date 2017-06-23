<?php

namespace app\common\model;

use think\Model;

class Link extends Model
{
    protected $pk = 'link_id';
    protected $table = 'blog_link';

    /*
     * 获取所有友情链接 并分页
     */
    public function getAll(){
        return $this->order('link_sort desc, link_id desc')->paginate(5);
    }

    /*
     * 添加友情链接
     */
    public function store($data)
    {
        //halt($data);
        $result = $this->validate(true)->save($data,$data['link_id']);
        if ($result) {
            return ['valid' => 1, 'msg' => '操作成功'];
        } else {
            return ['valid' => 0, 'msg' => $this->getError()];
        }
    }
    /*
     * 删除友链
     */
    public function del($link_id)
    {
        if(Link::destroy($link_id)){
            return ['valid' => 1, 'msg' => '操作成功'];
        }else{
            return ['valid' => 0, 'msg' => '操作失败'];
        }
    }
}
