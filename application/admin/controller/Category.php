<?php

namespace app\admin\controller;

use think\Controller;

class Category extends Controller
{
    protected $db;
    protected function _initialize()
    {
        parent::_initialize();
        $this->db = new \app\common\model\Category();
    }
    //栏目首页
    public function index()
    {
        //获取栏目的数据  https://www.kancloud.cn/manual/thinkphp5/135176
        //$field = db('cate')->select();
        $field = $this->db->getAll();
        $this->assign('field',$field);
        return $this->fetch();
    }

    //顶级栏目添加
    public function store()
    {
        if(request()->isPost())
        {
            //halt(input('post.'));
            $res = $this->db->store(input('post.'));
            if($res['valid']){
                //操作成功
                $this->success($res['msg'],'index');
                exit;
            }
            else{
                $this->error($res['msg'],'store');
            }
        }
        return $this->fetch();
    }


    //子栏目添加
    public function addSon()
    {
        //处理添加
        if(request()->isPost())
        {
            //halt(input('post.'));
            $res = $this->db->store(input('post.'));
            if($res['valid']){
                //操作成功
                $this->success($res['msg'],'index');
                exit;
            }
            else{
                $this->error($res['msg'],'store');
            }
        }

        //接收上级分类的ID 并把与ID对应的栏目查询出来
        // iput助手函数中的get只能获取通过查询字符串（即问号后面）传递的值，不能获取pathinfo地址参数的值，要获取pathinfo的值 需要用 param   https://www.kancloud.cn/manual/thinkphp5/118044
        $cate_id = input('param.cate_id');
        $data = $this->db->where('cate_id',$cate_id)->find();
        $this->assign('data',$data);
        return $this->fetch();
    }

    /*
     * 编辑分类
     */
    public function edit()
    {
        if(request()->isPost())
        {
            $res = $this->db->edit(input('post.'));
            if($res['valid']){
                //操作成功
                $this->success($res['msg'],'index');exit;
            }
            else{
                $this->error($res['msg']);
            }
        }
        $cate_id = input('param.cate_id');
        $oldData = $this->db->where('cate_id',$cate_id)->find();
        $this->assign('oldData',$oldData);
        //处理所属分类 【不能包含自身ID以及自身的子栏目】
        //将自身ID发送到模型，请求模型处理数据
        $cateDate = $this->db->getCateDate($cate_id);
        $this->assign('cateDate',$cateDate);
        return $this->fetch();
    }
    /*
     * 删除分类
     */
    public function del(){
        $res = $this->db->del(input('param.cate_id'));
        if($res['valid']){
            //操作成功
            $this->success($res['msg'],'index');exit;
        }
        else{
            $this->error($res['msg']);
        }
    }
}
