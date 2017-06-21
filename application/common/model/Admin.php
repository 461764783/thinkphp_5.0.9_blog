<?php

namespace app\common\model;

use houdunwang\crypt\Crypt;
use think\Loader;
use think\Model;
use think\Validate;

class Admin extends Model
{
    protected $pk = 'admin_id'; //主键
    protected $table = 'blog_admin';
    /*
     * 登陆
     */
    public function login($data)
    {
        //1、执行验证  使用 验证器 进行验证  https://www.kancloud.cn/manual/thinkphp5/129352
        $validate = Loader::validate('Admin');
        //如果验证不通过
        if(!$validate->check($data)){
            return ['valid'=>'0','msg'=>$validate->getError()];
            //dump($validate->getError());
        }
        //2、对比用户名和密码是否正确
        $userInfo = $this->where('admin_username',$data['admin_username'])->where('admin_password',Crypt::encrypt($data['admin_password']))->find();
        if(!$userInfo)
        {
            //未匹配到相关数据
            return ['valid'=>'0','msg'=>'用户名或密码不正确'];
        }
        //3、将用户信息存入到session
        session('admin.admin_id',$userInfo['admin_id']);
        session('admin.admin_username',$userInfo['admin_username']);
        return ['valid'=>'1','msg'=>'登录成功'];
    }
    /*
     * 修改密码
     */
    public function pass($data)
    {
        //1、执行验证   这里没有使用验证器进行验证  这里我们使用 独立验证  https://www.kancloud.cn/manual/thinkphp5/129352
        $validate = new Validate([
            'admin_password'  => 'require',
            'new_password' => 'require',
            'confirm_password' => 'require|confirm:new_password'    //内置的验证规则,这里使用的是confirm(字段比较类)：https://www.kancloud.cn/manual/thinkphp5/129356
        ],[
            'admin_password.require'  => '请输入原始密码',
            'new_password.require'  => '请输入新密码',
            'confirm_password.require'  => '请输入确认密码',
            'confirm.require'  => '确认密码和新密码不一致',
        ]);

        //如果验证不通过，则返回验证产生的提示信息
        if (!$validate->check($data)) {
            return ['valid'=>'0','msg'=>$validate->getError()];
            //dump($validate->getError());
        }
        //2、原始密码的判断
        $userInfo = $this->where('admin_id',session('admin.admin_id'))->where('admin_password',Crypt::encrypt($data['admin_password']))->find();
        if(!$userInfo)
        {
            return ['valid'=>'0','msg'=>'原始密码不正确'];
        }

        //3、修改密码   https://www.kancloud.cn/manual/thinkphp5/135189
        // save方法第二个参数为更新条件
        $res = $this->save([
            'admin_password'  => Crypt::encrypt($data['new_password'])
        ],[$this->pk => session('admin.admin_id')]);
        if($res)
        {
            return ['valid'=>'1','msg'=>'密码修改成功'];
        }else{
            return ['valid'=>'0','msg'=>'密码修改失败'];
        }
    }
}
