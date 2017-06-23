<?php

namespace app\admin\controller;

use think\Controller;

class Link extends Common
{
    protected $db;

    protected function _initialize()
    {
        parent::_initialize();
        $this->db = new \app\common\model\Link();
    }

    /*
     * 友链首页
     */
    public function index()
    {
        $list = $this->db->getAll();
        $this->assign('list', $list);
        return $this->fetch();
    }

    /*
     * 添加友链
     */
    public function store()
    {
        $link_id = input('param.link_id');
        if (request()->isPost()) {
            $res = $this->db->store(input('post.'));
            if ($res['valid']) {
                $this->success($res['msg'], 'index');
                exit;
            } else {
                $this->error($res['msg']);
            }
        }

        if ($link_id) {
            //如果有link_id则是编辑   编辑页面
            $oldDate = $this->db->find($link_id);
        } else {
            //添加页面
            $oldDate = ['link_name' => '', 'link_url' => '', 'link_sort' => 100];
        }
        $this->assign('oldDate', $oldDate);
        return $this->fetch();
    }
    /*
     * 删除友链
     */
    public function del(){
        $link_id = input('get.link_id');
        $res = $this->db->del($link_id);
        if($res['valid']){
            $this->success($res['msg'], 'index');
        }else{
            $this->error($res['msg']);
        }
    }
}
