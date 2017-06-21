<?php

namespace app\common\model;

use think\Model;

class Article extends Model
{
    protected $pk = 'arc_id';
    protected $table = 'blog_article';
    protected $auto = ['admin_id'];
    protected $insert = ['sendtime'];
    protected $update = ['updatetime'];

    protected function setAdminIdAttr()
    {
        return session('admin.admin_id');
    }
    protected function setSendTimeAttr()
    {
        return time();
    }
    protected function setUpdateTimeAttr()
    {
        return time();
    }

    public function store($data)
    {
        $result = $this->validate(true)->save($data);
        if ($result) {
            return ['valid' => 1, 'msg' => '添加成功'];
        } else {
            return ['valid' => 0, 'msg' => $this->getError()];
        }
    }


}
