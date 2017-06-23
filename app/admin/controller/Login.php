<?php

namespace app\admin\controller;
use app\common\model\Admin;
use houdunwang\crypt\Crypt;
use think\Controller;

class Login extends Controller
{
    public function login()
    {
        //echo Crypt::encrypt('admin888');

        //测试数据库连接  数据库连接配置在application 下的database.php
        //$data = db('admin')->find(1);
        //dump($data);
        if(request()->isPost()){
            $res = (new Admin())->login(input('post.'));
            if($res['valid'])
            {
                $this->success($res['msg'],'admin/entry/index');exit;
            }else{
                $this->error($res['msg']);
            }
        }
        return $this->fetch();
    }
}
