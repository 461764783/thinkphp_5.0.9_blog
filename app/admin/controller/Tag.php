<?php

namespace app\admin\controller;

use think\Controller;
use think\Db;

class Tag extends Common
{
    protected $db;
    public function _initialize()
    {
        parent::_initialize();
        $this->db = new \app\common\model\Tag();
    }

    public function index()
    {
        //分页实现方法 https://www.kancloud.cn/manual/thinkphp5/154294
        $field =  Db::name('tag')->paginate(5);
        $this->assign('field',$field);
        return $this->fetch();
    }

    /**
     *  添加标签
     */
    public function store()
    {
        $tag_id = input('param.tag_id');
        if(request()->isPost())
        {
            $res = $this->db->store(input('post.'));
            if($res['valid']){
                $this->success($res['msg'],'index');exit;
            }
            else{
                $this->error($res['msg']);
            }
        }
        if($tag_id)
        {
            //如果有请求的tag_id则说明是编辑否则就是添加
            $oldDate = $this->db->find($tag_id);
        }else{
            $oldDate = ['tag_name'=>''];
        }
        $this->assign('oldDate',$oldDate);
        return $this->fetch();
    }

    public function del()
    {
        $tag_id = input('get.tag_id');
        if(\app\common\model\Tag::destroy($tag_id))
        {
            $this->success('删除成功','index');exit;
        }
        else{
            $this->error('删除失败');exit;
        }
    }
}
