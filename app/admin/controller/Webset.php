<?php

namespace app\admin\controller;

use think\Controller;

class Webset extends Common
{
    protected $db;
    public function _initialize()
    {
        parent::_initialize();
        $this->db = new \app\common\model\Webset();
    }

    public function index()
    {
        $field = db('webset')->select();
        $this->assign('field',$field);
        return $this->fetch();
    }
    public function edit(){
        if(request()->isAjax()){
            $res  = $this->db->edit(input('post.'));
            if($res['valid']){
                $this->success($res['msg'],'index');
            }else{
                $this->error($res['msg']);
            }
        }
    }
}
