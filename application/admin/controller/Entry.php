<?php

namespace app\admin\controller;
use app\common\model\Admin;

class Entry extends Common
{
    //后台首页
    public function index()
    {
        //加载模板文件
        return $this->fetch();
    }

    /**
     * 修改登陆密码
     */
    public function pass()
    {
        if(request()->isPost())
        {
            //将post提交的数据交给模型处理
            $res =(new Admin())->pass(input('post.'));
            //根据模型中返回的值判断操作是不成功
            if($res['valid'])
            {
                //执行成功

                 //清除session
                session(null);
                $this->success($res['msg'],'admin/entry/index');exit;
            }else{
                //执行失败
                $this->error($res['msg']);
            }
        }
        //加载模板文件
        return $this->fetch();
    }
}
