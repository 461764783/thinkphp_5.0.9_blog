<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;

class Common extends Controller
{
    public function __construct(Request $request = null)
    {
        parent::__construct( $request );
        //执行登陆验证
        //下面的sesseion 方法是TP5提供的方法； 这里的admin.admin_id相当于原生PHP中的  $_SESSEION['admin']['admin_id']
        if(!session('admin.admin_id'))
        {
            $this->redirect('admin/login/login');
        }
    }
}
