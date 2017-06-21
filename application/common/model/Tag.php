<?php

namespace app\common\model;

use think\Model;

class Tag extends Model
{
    protected $pk = 'tag_id';
    protected $table = 'blog_tag';
    /*
     * 添加标签
     */
    public function store($data)
    {
        //如果tag_id没有就是添加，否则就是编辑
        $result = $this->validate(true)->save($data,$data['tag_id']);
        if($result)
        {
            return ['valid'=>1,'msg'=>'添加成功'];
        }
        else{
            return ['valid'=>1,'msg'=>$this->getError()];
        }
    }
}
